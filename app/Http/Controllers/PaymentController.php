<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;
use File;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Payment';

        $data = Payment::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc')
                        ->paginate(5);

        return view('admin.payment.index', compact('data', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Add Payment";

        return view('admin.payment.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        try {
            $data = $request->validated();

            if($request->hasFile('image')) {
                $image = $request->file('image');
                // Nama image
                $nama_photo = date('Y-m-d_His').$image->getClientOriginalName();
                // Simpan ke direktori
                $image->move('image/payment/'.date('Y-m').'/', $nama_photo);
                // Nama Image File
                $data['image'] = 'image/payment/'.date('Y-m') .'/'. $nama_photo;    
            }else{
                $data['image'] = null;
            }

            // Status default
            $data['status'] = 1;

            Payment::create($data);

            return redirect()->route('payment.index')->with('success', 'Success');
        } catch (\Exception $e) {
            return redirect()->route('payment.create')->with('error', 'Failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $title = 'Edit Payment';

        $data = $payment;

        return view('admin.payment.edit', compact('title', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        try {
            // Validasi data request
            $data = $request->validated();

            if($request->hasFile('image')) {
                File::delete($payment->image);
                $image = $request->file('image');
                // Nama image
                $nama_photo = date('Y-m-d_His').$image->getClientOriginalName();
                // Simpan ke direktori
                $image->move('image/payment/'.date('Y-m').'/', $nama_photo);
                // Nama Image File
                $data['image'] = 'image/payment/'.date('Y-m') .'/'. $nama_photo;    
            }else{
                $data['image'] = $payment->image;
            }

            // Update data payment yang ada
            $payment->update($data);

            return redirect()->route('payment.index')->with('success', 'payment updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('payment.edit', $payment->id)->with('error', 'Failed to update payment');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        try {
            $payment->delete(); // Melakukan soft delete
            return redirect()->route('payment.index')->with('success', 'success');
        } catch (\Exception $e) {
            return redirect()->route('payment.index')->with('error', 'failed');
        }
    }

    public function search(Request $request)
    {
        $title = "Search Payment";   
        $search = $request->search;

        // Mulai query
        $query = Payment::whereNull('deleted_at')
                        ->orderBy('created_at', 'desc');

        // Jika ada pencarian, terapkan filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan paginasi
        $data = $query->paginate(5);

        return view('admin.payment.index', compact('title', 'data'));
    }
}
