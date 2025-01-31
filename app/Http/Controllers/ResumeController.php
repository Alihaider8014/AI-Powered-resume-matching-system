<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenAIService;
use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;


class ResumeController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function uploadResumes(Request $request)
    {
        $jobDescription = $request->input('job_description');
        $resumes = [];

        foreach ($request->file('resumes') as $resume) {
            $pdfText = $this->extractTextFromPdf($resume->getPathname());
            $resumes[] = $pdfText;
        }

        $result = $this->openAIService->analyzeResumes($jobDescription, $resumes);

        return response()->json([
            'result' => $result,
        ]);
    }
    
    public function  extractTextFromPdf($filePath) {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        return $pdf->getText();
    }
}
