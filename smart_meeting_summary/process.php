<?php
 
header('Content-Type: application/json');
 
require_once '../vendor/autoload.php';
require_once 'config.php';
 
use SmartSummary\AiService;
 
try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests are allowed');
    }
 
    $transcript = $_POST['transcript'] ?? '';
    $section = $_POST['section'] ?? null;
 
    if (empty(trim($transcript))) {
        throw new Exception('Transcript is required');
    }
 
    $config = include 'config.php';
    $aiService = new AiService($config);
 
    $summary = $aiService->generateSummary($transcript, $section);
 
    echo json_encode([
        'success' => true,
        'summary' => $summary
    ]);
 
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}