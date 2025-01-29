<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Room;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua reservasi dari database
        $reservations = Reservation::with('room')->get();

        // Kembalikan view dengan data reservasi
        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil daftar kamar yang tersedia
        $rooms = Room::where('status', 'available')->get();

        // Kembalikan view dengan daftar kamar
        return view('reservations.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:15',
            'guest_count' => 'required|integer|min:1',
        ]);

        // Pastikan kamar tersedia untuk rentang tanggal
        $room = Room::findOrFail($request->room_id);
        $overlapReservations = Reservation::where('room_id', $room->id)
            ->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
            ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
            ->exists();

        if ($overlapReservations) {
            return redirect()->back()->with('error', 'Kamar tidak tersedia untuk tanggal tersebut.');
        }

        // Hitung total biaya
        $nightCount = Carbon::parse($request->check_in_date)->diffInDays(Carbon::parse($request->check_out_date));
        $totalCost = $room->price_per_night * $nightCount;

        // Simpan reservasi
        $reservation = Reservation::create([
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'guest_count' => $request->guest_count,
            'total_cost' => $totalCost,
        ]);

        // Tandai kamar sebagai terisi
        $room->status = 'occupied';
        $room->save();

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dibuat!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        // Kembalikan view dengan data reservasi
        return view('reservations.show', compact('reservation'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Validasi input
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email|max:255',
            'guest_phone' => 'required|string|max:15',
            'guest_count' => 'required|integer|min:1',
        ]);

        // Periksa ketersediaan kamar
        $overlapReservations = Reservation::where('room_id', $request->room_id)
            ->where('id', '!=', $reservation->id) // Abaikan reservasi ini
            ->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
            ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
            ->exists();

        if ($overlapReservations) {
            return redirect()->back()->with('error', 'Kamar tidak tersedia untuk tanggal tersebut.');
        }

        // Perbarui data reservasi
        $reservation->update([
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'guest_count' => $request->guest_count,
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil diperbarui!');
    }


    /**
     * fungsi memperpanjang
     */
    public function extendReservation(Request $request, $reservationId)
    {
        // Validasi input
        $request->validate([
            'new_check_out_date' => 'required|date|after:check_in_date',
        ]);

        // Ambil reservasi yang ingin diubah
        $reservation = Reservation::findOrFail($reservationId);
        $room = $reservation->room; // Ambil kamar terkait

        // Ambil tanggal check-out baru
        $newCheckOutDate = Carbon::parse($request->new_check_out_date);
        $currentCheckOutDate = Carbon::parse($reservation->check_out_date);

        // Periksa apakah kamar tersedia untuk tanggal baru
        if ($newCheckOutDate->gt($currentCheckOutDate)) {
            // Cari reservasi lain di kamar yang sama dalam rentang tanggal baru
            $overlapReservations = Reservation::where('room_id', $room->id)
                ->whereBetween('check_in_date', [$currentCheckOutDate, $newCheckOutDate])
                ->orWhereBetween('check_out_date', [$currentCheckOutDate, $newCheckOutDate])
                ->exists();

            if ($overlapReservations) {
                return redirect()->back()->with('error', 'Kamar tidak tersedia untuk tanggal tersebut.');
            }
        }

        // Update tanggal check-out
        $reservation->check_out_date = $newCheckOutDate;

        // Hitung ulang total biaya
        $nightCount = $newCheckOutDate->diffInDays(Carbon::parse($reservation->check_in_date));
        $reservation->total_cost = $room->price_per_night * $nightCount;

        // Simpan pembaruan
        $reservation->save();

        return redirect()->route('reservations.show', $reservationId)->with('success', 'Reservasi berhasil diperpanjang!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        // Hapus reservasi
        $reservation->delete();

        // Periksa jika kamar terkait sudah tidak ada reservasi aktif, ubah status ke "available"
        if (!Reservation::where('room_id', $reservation->room_id)->exists()) {
            $room = Room::findOrFail($reservation->room_id);
            $room->status = 'available';
            $room->save();
        }

        return redirect()->route('reservations.index')->with('success', 'Reservasi berhasil dihapus!');
    }

}
