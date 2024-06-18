<?php

namespace App\Http\Controllers;

use App\Models\Certificates;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class CertificatesController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            "certificate"=>'file|mimes:pdf|max:2048',
            "hpv_certificate"=>"file|mimes:pdf|max:2048",
            "child_id"=>"required"
        ]);

        $certificatesPath = null;
        $hpvCertificatesPath = null;

        $request->hasFile("certificate") &&
            $certificatesPath = $request->file('certificate')->store('certificates','public');

        $request->hasFile("hpv_certificate") &&
            $hpvCertificatesPath = $request->file('hpv_certificate')->store('public/hpv_certificates');

       $certificate =  Certificates::create([
              "certificate"=>$certificatesPath,
            "hpv_certificate"=>$hpvCertificatesPath,
             "child_id"=>$request->child_id
        ]);

       if($certificate){
           return response()->json(["message"=>"Certificate Stored Successfully", "status"=>201]);
       } else {
           return response()->json(["message"=>"Certificate Store Failed", "status"=>500]);
       }
    }

    public function show(string $card_no): JsonResponse
    {
        $certificate = Certificates::where('child_id', $card_no)->first();

        if (!$certificate) {
            return response()->json(['error' => 'Certificate not found'], 404);
        }

        return response()->json($certificate, 200);
    }

}
