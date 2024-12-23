<?php declare(strict_types=1);

namespace SingOff\Tests\Includes;

use PHPUnit\Framework\TestCase;
use TCPDF;

final class GeneratePDFTest extends TestCase
{


public function testAutoloadFileExists(): void
{
    $autoloadPath = dirname(__DIR__) . '/vendor/autoload.php';
    $this->assertFileExists($autoloadPath, 'The autoload.php file does not exist in the specified path.');
}


public function testRequiredDependenciesAreLoaded(): void
{
    $this->assertTrue(class_exists('TCPDF'), 'TCPDF class should be available after requiring autoload.php');
}


public function testGeneratePDFWithEmptyFormData(): void
{
    $this->expectException(\TypeError::class);
    
    $formData = [];
    generatePDF($formData);
}



public function testGeneratePDFThrowsExceptionIfAutoloadNotFound(): void
{
    $this->expectException(\PHPUnit\Framework\Exception::class);
    $this->expectExceptionMessage('Failed to open stream: No such file or directory');

    $formData = [];
    $originalAutoloadPath = '../vendor/autoload.php';
    $nonExistentPath = '../vendor/non_existent_autoload.php';

    rename($originalAutoloadPath, $nonExistentPath);

    try {
        generatePDF($formData);
    } finally {
        rename($nonExistentPath, $originalAutoloadPath);
    }
}


public function testGeneratePDFCreatesValidPDFWithCorrectContent(): void
public function testGeneratePDFCreatesValidPDFWithCorrectContent(): void
{
    $formData = [
        'installation_date' => '2023-05-01',
        'radio_number' => 'R123',
        'unit_code' => 'UC456',
        'x_number' => 'X789',
        'radio_mobile_mid' => 'RM101',
        'license_plate' => 'ABC123',
        'mileage' => '50000',
        'make' => 'Toyota',
        'model' => 'Camry',
        'avl1_check' => 'Yes',
        'previously_installed' => 'No',
        'system_test' => 'Pass',
        'installation_type' => 'New',
        'installer_name' => 'John Doe',
        'calfire_officer_name' => 'Jane Smith',
        'device_data' => '{"sku":"DEV001","serial":"S123","asset":"A456","variant":"V1"}',
        'installer_signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        'calfire_signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg=='
    ];

    $pdfContent = $this->getPDFContent($formData);

    $this->assertNotEmpty($pdfContent, 'PDF content should not be empty');
    $this->assertStringStartsWith('%PDF-', $pdfContent, 'PDF content should start with %PDF-');
    $this->assertStringContainsString('Installation Form Details', $pdfContent, 'PDF should contain the title "Installation Form Details"');
    $this->assertStringContainsString('2023-05-01', $pdfContent, 'PDF should contain the installation date');
    $this->assertStringContainsString('R123', $pdfContent, 'PDF should contain the radio number');
    $this->assertStringContainsString('John Doe', $pdfContent, 'PDF should contain the installer name');
    $this->assertStringContainsString('Jane Smith', $pdfContent, 'PDF should contain the CalFire officer name');
}





public function testGeneratePDFHandlesSpecialCharactersCorrectly(): void
{
    $formData = [
        'installation_date' => '2023-05-01',
        'radio_number' => 'R123!@#$%^&*()',
        'unit_code' => 'UC456<>"\'',
        'x_number' => 'X789ñáéíóú',
        'installer_name' => 'John Doe™®©',
    ];

    $pdfContent = $this->getPDFContent($formData);

    $this->assertStringContainsString('R123!@#$%^&*()', $pdfContent, 'PDF should contain the radio number with special characters');
    $this->assertStringContainsString('UC456<>"\'', $pdfContent, 'PDF should contain the unit code with special characters');
    $this->assertStringContainsString('X789ñáéíóú', $pdfContent, 'PDF should contain the x number with special characters');
    $this->assertStringContainsString('John Doe™®©', $pdfContent, 'PDF should contain the installer name with special characters');
}


public function testGeneratePDFWithDifferentDataSizes(): void
{
    $smallFormData = [
        'installation_date' => '2023-05-01',
        'radio_number' => 'R123',
        'unit_code' => 'UC456'
    ];

    $largeFormData = [
        'installation_date' => '2023-05-01',
        'radio_number' => 'R123',
        'unit_code' => 'UC456',
        'x_number' => 'X789',
        'radio_mobile_mid' => 'RM101',
        'license_plate' => 'ABC123',
        'mileage' => '50000',
        'make' => 'Toyota',
        'model' => 'Camry',
        'avl1_check' => 'Yes',
        'previously_installed' => 'No',
        'system_test' => 'Pass',
        'installation_type' => 'New',
        'installer_name' => 'John Doe',
        'calfire_officer_name' => 'Jane Smith',
        'device_data' => '{"sku":"DEV001","serial":"S123","asset":"A456","variant":"V1"}',
        'installer_signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        'calfire_signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg=='
    ];

    $smallPdfContent = $this->getPDFContent($smallFormData);
    $largePdfContent = $this->getPDFContent($largeFormData);

    $this->assertNotEmpty($smallPdfContent, 'Small PDF content should not be empty');
    $this->assertNotEmpty($largePdfContent, 'Large PDF content should not be empty');
    $this->assertGreaterThan(strlen($smallPdfContent), strlen($largePdfContent), 'Large PDF should be bigger than small PDF');
}



public function testGeneratePDFCreatesReadableAndUncorruptedPDF(): void
{
    $formData = [
        'installation_date' => '2023-05-01',
        'radio_number' => 'R123',
        'unit_code' => 'UC456',
        'x_number' => 'X789',
        'installer_name' => 'John Doe',
        'calfire_officer_name' => 'Jane Smith'
    ];

    ob_start();
    generatePDF($formData);
    $pdfContent = ob_get_clean();

    $this->assertNotEmpty($pdfContent, 'PDF content should not be empty');
    $this->assertStringStartsWith('%PDF-', $pdfContent, 'PDF content should start with %PDF-');
    $this->assertStringEndsWith('%%EOF', trim($pdfContent), 'PDF content should end with %%EOF');

    $tmpfname = tempnam(sys_get_temp_dir(), 'test_pdf');
    file_put_contents($tmpfname, $pdfContent);

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $this->assertEquals('application/pdf', $finfo->file($tmpfname), 'File should be a valid PDF');

    unlink($tmpfname);
}

public function testValidateFormDataStructureBeforePDFGeneration(): void
{
    $invalidFormData = [
        'installation_date' => '2023-05-01',
        'radio_number' => 'R123',
        // Missing required fields
    ];

    $this->expectException(\InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid form data structure');

    generatePDF($invalidFormData);
}

public function testGeneratePDFWithLargeFormData(): void
{
    $largeFormData = [];
    for ($i = 0; $i < 10000; $i++) {
        $largeFormData["key_$i"] = str_repeat('a', 1000);
    }

    $memoryBefore = memory_get_usage(true);

    generatePDF($largeFormData);

    $memoryAfter = memory_get_usage(true);
    $memoryDifference = $memoryAfter - $memoryBefore;

    $this->assertLessThan(50 * 1024 * 1024, $memoryDifference, 'Memory usage increase should be less than 50MB');
}


}
