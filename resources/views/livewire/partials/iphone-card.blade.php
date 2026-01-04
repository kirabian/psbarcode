@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    
    // Warna tema internal
    $bgMain = $isDark ? '#000000' : '#ffffff';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000'; 
    $labelColor = $isDark ? '#8e8e93' : '#000000';

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
        ['label' => 'EID', 'val' => $finalEid],
        ['label' => 'IMEI', 'val' => $item['imei1']],
        ['label' => 'IMEI2', 'val' => $item['imei2']],
        ['label' => 'MEID', 'val' => $item['meid']],
    ];
@endphp

<div class="d-flex flex-column align-items-center">
    
    <div id="capture-area-{{ $id }}" style="
        width: 375px; 
        height: 812px; 
        background-color: {{ $bgMain }}; 
        color: {{ $textColor }}; 
        font-family: -apple-system, BlinkMacSystemFont, sans-serif; 
        position: relative; 
        overflow: hidden; 
        display: flex; 
        flex-direction: column;
        border-radius: 46px; /* Memberikan lekukan khas iPhone di hasil download */
    ">
        
        <div style="display: flex; justify-content: space-between; padding: 18px 30px 0 30px; align-items: center; width: 100%; box-sizing: border-box;">
            <div style="font-weight: 600; font-size: 15px; color: {{ $headerColor }};">
                {{ $item['hour'] ?? '12' }}:{{ $item['minute'] ?? '26' }}
            </div>

            <div style="display: flex; gap: 6px; align-items: center;">
                <svg width="18" height="12" viewBox="0 0 18 12" fill="{{ $headerColor }}">
                    <rect x="0" y="7" width="3" height="5" rx="1"/>
                    <rect x="4" y="5" width="3" height="7" rx="1"/>
                    <rect x="8" y="3" width="3" height="9" rx="1"/>
                    <rect x="12" y="0" width="3" height="12" rx="1" opacity="0.3"/>
                </svg>
                <i class="fas fa-wifi" style="font-size: 12px; color: {{ $headerColor }};"></i>
                <div style="width: 25px; height: 12px; border: 1px solid {{ $headerColor }}; border-radius: 3px; position: relative; padding: 1px;">
                    <div style="width: 18px; height: 100%; background: #34C759; border-radius: 1px;"></div>
                    <div style="width: 2px; height: 4px; background: {{ $headerColor }}; position: absolute; right: -4px; top: 3px; border-radius: 0 1px 1px 0;"></div>
                </div>
            </div>
        </div>

        <div style="padding: 15px 25px;">
            <span style="color: #0A84FF; font-size: 18px; font-weight: 400;">Cancel</span>
        </div>

        <div style="flex-grow: 1; display: flex; flex-direction: column; align-items: center; padding-top: 10px;">
            <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 25px; color: {{ $textColor }};">Device Info</h1>

            @foreach($fields as $field)
            <div style="margin-bottom: 25px; width: 100%; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 85%; text-align: center; margin-bottom: 6px;">
                    <span style="font-size: 12px; font-weight: 500; color: {{ $labelColor }}; word-break: break-all;">
                        {{ $field['label'] }} {{ $field['val'] }}
                    </span>
                </div>
                <div style="background: #fff; padding: 10px; width: 90%; border-radius: 4px; display: flex; justify-content: center;">
                    <svg class="barcode-svg" 
                         data-value="{{ $field['val'] }}" 
                         data-format="CODE128" 
                         data-height="50" 
                         data-width="4" 
                         data-displayValue="false" 
                         style="width: 100%; height: 50px;"></svg>
                </div>
            </div>
            @endforeach
        </div>

        <div style="width: 134px; height: 5px; background: {{ $textColor }}; border-radius: 10px; margin: 0 auto 10px auto;"></div>
    </div>

    <button onclick="downloadImage('{{ $id }}')" class="btn btn-success mt-4 rounded-pill px-4 fw-bold">
        Download JPG
    </button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadImage(id) {
    const element = document.getElementById('capture-area-' + id);
    
    html2canvas(element, {
        backgroundColor: null, // Menghilangkan background tambahan
        scale: 2, // Kualitas tinggi
        useCORS: true,
        borderRadius: 46 // Menjaga lekukan iPhone tetap ada di file gambar
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'PSTORE-DeviceInfo-' + id + '.jpg';
        link.href = canvas.toDataURL('image/jpeg', 0.9);
        link.click();
    });
}
</script>