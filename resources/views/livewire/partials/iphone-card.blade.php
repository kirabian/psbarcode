@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    
    // Warna tema internal
    $bgMain = $isDark ? '#000000' : '#ffffff';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000'; 
    $labelColor = $isDark ? '#8e8e93' : '#000000';

    // Logika 33 digit EID
    $rawEid = (string)($item['eid'] ?? '');
    if (strlen($rawEid) < 33) {
        $finalEid = '8904' . str_pad(mt_rand(0, 999999999), 29, '0', STR_PAD_LEFT);
    } else {
        $finalEid = substr($rawEid, 0, 33);
    }

    $fields = [
        ['label' => 'EID', 'val' => $finalEid],
        ['label' => 'IMEI', 'val' => $item['imei1'] ?? ''],
        ['label' => 'IMEI2', 'val' => $item['imei2'] ?? ''],
        ['label' => 'MEID', 'val' => $item['meid'] ?? ''],
    ];
@endphp

<div class="d-flex flex-column align-items-center" style="background: transparent;">
    
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
        border-radius: 50px; 
        -webkit-font-smoothing: antialiased;
    ">
        
        <div style="display: flex; justify-content: space-between; padding: 18px 30px 0 30px; align-items: center; width: 100%; box-sizing: border-box;">
            <div style="font-weight: 600; font-size: 15px; color: {{ $headerColor }};">
                {{ $item['hour'] ?? '12' }}:{{ $item['minute'] ?? '29' }}
            </div>

            <div style="display: flex; gap: 6px; align-items: center;">
                <svg width="18" height="12" viewBox="0 0 18 12" fill="{{ $headerColor }}">
                    <rect x="0" y="7" width="3" height="5" rx="1"/>
                    <rect x="4" y="5" width="3" height="7" rx="1"/>
                    <rect x="8" y="3" width="3" height="9" rx="1"/>
                    <rect x="12" y="0" width="3" height="12" rx="1" opacity="0.3"/>
                </svg>
                <svg width="17" height="12" viewBox="0 0 17 12" fill="{{ $headerColor }}">
                    <path d="M8.5 12L6.5 9.5H10.5L8.5 12Z"/>
                    <path opacity="0.3" d="M8.5 4.5C6.6 4.5 4.9 5.2 3.6 6.4L5 7.8C5.9 7 7.1 6.5 8.5 6.5C9.9 6.5 11.1 7 12 7.8L13.4 6.4C12.1 5.2 10.4 4.5 8.5 4.5Z"/>
                    <path d="M8.5 0.5C5.3 0.5 2.3 1.8 0.3 4L1.7 5.4C3.4 3.7 5.8 2.5 8.5 2.5C11.2 2.5 13.6 3.7 15.3 5.4L16.7 4C14.7 1.8 11.7 0.5 8.5 0.5Z"/>
                </svg>
                <div style="width: 25px; height: 12px; border: 1px solid {{ $headerColor }}; border-radius: 3px; position: relative; padding: 1px;">
                    <div style="width: 80%; height: 100%; background: #34C759; border-radius: 1px;"></div>
                    <div style="width: 2px; height: 4px; background: {{ $headerColor }}; position: absolute; right: -4px; top: 3px; border-radius: 0 1px 1px 0;"></div>
                </div>
            </div>
        </div>

        <div style="padding: 15px 30px;">
            <span style="color: #0A84FF; font-size: 19px; font-weight: 400;">Cancel</span>
        </div>

        <div style="flex-grow: 1; display: flex; flex-direction: column; align-items: center; padding-top: 10px;">
            <h1 style="font-size: 34px; font-weight: 700; margin-bottom: 35px; color: {{ $textColor }};">Device Info</h1>

            @foreach($fields as $field)
            <div style="margin-bottom: 30px; width: 100%; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 90%; text-align: center; margin-bottom: 8px;">
                    <span style="font-size: 13px; font-weight: 500; color: {{ $labelColor }}; word-break: break-all;">
                        {{ $field['label'] }} {{ $field['val'] }}
                    </span>
                </div>
                <div style="background: #ffffff; padding: 12px 10px; width: 90%; border-radius: 4px; display: flex; justify-content: center;">
                    <svg class="barcode-svg" 
                         data-value="{{ $field['val'] }}" 
                         data-format="CODE128" 
                         data-height="50" 
                         data-width="4" 
                         data-displayValue="false" 
                         style="width: 100%; height: 55px;"></svg>
                </div>
            </div>
            @endforeach
        </div>

        <div style="width: 134px; height: 5px; background-color: {{ $textColor }}; border-radius: 100px; margin: 0 auto 10px auto;"></div>
    </div>

    <button onclick="downloadPng('{{ $id }}')" class="btn btn-primary mt-4 rounded-pill px-4 fw-bold">
        Download PNG (No Black Border)
    </button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadPng(id) {
    const element = document.getElementById('capture-area-' + id);
    
    html2canvas(element, {
        backgroundColor: null, // Menjadikan area luar transparan
        scale: 3, 
        useCORS: true,
        logging: false,
        allowTaint: true
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'PSTORE-DeviceInfo-' + id + '.png'; // Format diubah ke PNG
        link.href = canvas.toDataURL('image/png'); // Format data diubah ke PNG
        link.click();
    });
}
</script>