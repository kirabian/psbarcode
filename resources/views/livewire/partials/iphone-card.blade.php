@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    
    // Warna background & Teks
    $bgCard = $isDark ? '#1c1c1e' : '#ffffff';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $labelColor = $isDark ? '#ffffff' : '#000000';

    // Logika pengunci 33 digit EID
    $rawEid = (string)($item['eid'] ?? '');
    if (strlen($rawEid) < 33) {
        $needed = 33 - strlen('8904');
        $randomDigits = '';
        for($i=0; $i<$needed; $i++) { $randomDigits .= mt_rand(0,9); }
        $finalEid = '8904' . $randomDigits;
    } else {
        $finalEid = substr($rawEid, 0, 33);
    }

    $fields = [
        ['label' => 'EID', 'val' => $finalEid, 'barHeight' => 55],
        ['label' => 'IMEI', 'val' => $item['imei1'], 'barHeight' => 55],
        ['label' => 'IMEI2', 'val' => $item['imei2'], 'barHeight' => 55],
        ['label' => 'MEID', 'val' => $item['meid'], 'barHeight' => 55],
    ];
@endphp

<div id="{{ $id }}" style="
    width: 375px; 
    background-color: {{ $bgCard }}; 
    color: {{ $textColor }}; 
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
    padding: 20px 0; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    -webkit-font-smoothing: antialiased;
">
    
    <div style="width: 100%; padding: 0 24px; margin-bottom: 20px;">
        <span style="color: #0A84FF; font-size: 19px; font-weight: 400; cursor: pointer;">Cancel</span>
    </div>

    <div style="width: 100%; text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 34px; font-weight: 700; margin: 0; color: {{ $textColor }};">Device Info</h1>
    </div>

    @foreach($fields as $field)
    <div style="margin-bottom: 35px; width: 100%; display: flex; flex-direction: column; align-items: center;">
        
        <div style="width: 90%; text-align: center; margin-bottom: 10px;">
            <span style="font-size: 14px; font-weight: 500; color: {{ $labelColor }}; word-break: break-all;">
                {{ $field['label'] }} {{ $field['val'] }}
            </span>
        </div>

        <div style="
            background-color: #ffffff; 
            padding: 15px 10px; 
            width: 92%; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            border-radius: 4px;
            box-sizing: border-box;
        ">
            <svg class="barcode-svg" 
                 data-value="{{ $field['val'] }}" 
                 data-format="CODE128" 
                 data-height="100" 
                 data-width="4" 
                 data-displayValue="false" 
                 data-margin="0"
                 preserveAspectRatio="none"
                 style="width: 100%; height: {{ $field['barHeight'] }}px;"></svg>
        </div>
    </div>
    @endforeach

    <div style="width: 134px; height: 5px; background-color: {{ $textColor }}; border-radius: 100px; margin-top: 20px; opacity: 0.8;"></div>
</div>