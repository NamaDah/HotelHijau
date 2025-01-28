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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
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
        //
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
        $room = $reservation->room; // Ambil kamar yang dipesan saat ini

        // Ambil tanggal check-out baru
        $newCheckOutDate = Carbon::parse($request->new_check_out_date);
        $currentCheckOutDate = Carbon::parse($reservation->check_out_date);

        // Periksa apakah kamar yang dipesan sudah terisi
        if ($newCheckOutDate->gt($currentCheckOutDate)) {
            // Cari reservasi lain di kamar yang sama dalam rentang tanggal baru
            $overlapReservations = Reservation::where('room_id', $room->id)
                ->whereBetween('check_in_date', [$currentCheckOutDate, $newCheckOutDate])
                ->orWhereBetween('check_out_date', [$currentCheckOutDate, $newCheckOutDate])
                ->exists();

            if ($overlapReservations) {
                // Kamar yang dipilih sudah terisi, cari kamar lain yang tersedia dengan jenis yang berbeda
                $availableRoom = Room::where('status', 'available')
                    ->where('room_type', '!=', $room->room_type) // Mencari kamar dengan jenis berbeda
                    ->first();

                if ($availableRoom) {
                    // Pindahkan reservasi ke kamar yang tersedia
                    $reservation->room_id = $availableRoom->id;
                    $reservation->check_out_date = $newCheckOutDate;

                    // Hitung ulang total biaya
                    $nightCount = $newCheckOutDate->diffInDays(Carbon::parse($reservation->check_in_date));
                    $reservation->total_cost = $availableRoom->price_per_night * $nightCount;

                    // Update status kamar: Kamarnya jadi terisi, yang lama jadi tersedia
                    $room->status = 'available';
                    $availableRoom->status = 'occupied';

                    // Simpan pembaruan
                    $reservation->save();
                    $room->save();
                    $availableRoom->save();

                    return redirect()->route('reservations.show', $reservationId)
                        ->with('success', 'Reservasi berhasil dipindahkan ke kamar lain yang tersedia.');
                } else {
                    // Jika tidak ada kamar lain yang tersedia
                    return redirect()->back()->with('error', 'Tidak ada kamar lain yang tersedia untuk tanggal tersebut.');
                }
            }
        }

        // Cek jika semua kamar sudah penuh
        $allRoomsOccupied = Room::where('status', 'available')->count() == 0;

        if ($allRoomsOccupied) {
            // Jika semua kamar sudah terisi
            return redirect()->back()->with('error', 'Semua kamar sudah penuh. Tidak dapat memperpanjang masa menginap.');
        }

        // Jika kamar yang dipesan tidak terisi atau tidak ada perubahan
        return redirect()->route('reservations.show', $reservationId)
            ->with('success', 'Reservasi berhasil diperpanjang!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
