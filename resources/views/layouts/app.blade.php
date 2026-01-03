<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PStore IMEI Generator</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* CSS Khusus agar tampilan cetak (Print) sempurna */
        @media print {
            .no-print { display: none !important; }
            body { background-color: white !important; -webkit-print-color-adjust: exact; }
            .page-break { page-break-after: always; }
        }
        body { background-color: #f3f4f6; }
    </style>
    
    @livewireStyles
</head>
<body>
    
    <div class="container mx-auto py-10">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>