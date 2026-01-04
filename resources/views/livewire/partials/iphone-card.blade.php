@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    $bgMain = $isDark ? '#000000' : '#f6f8f5';
    $bgCard = $isDark ? '#1c1c1e' : '#ffffff';
    $bgBack = $isDark ? '#141414' : '#d6d6d6';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000'; 
    $labelColor = $isDark ? '#ffffff' : '#000000';

    $battFillColor = ($item['batteryLevel'] ?? 100) < 20 ? '#FF3B30' : '#34C759';
    $battTextColor = $item['battTextRandom'] ?? ($isDark ? '#ffffff' : '#000000'); 
    
    $rawEid = (string)($item['eid'] ?? '');
    if (strlen($rawEid) < 33) {
        $needed = 33 - strlen('8904');
        $randomDigits = '';
        for($i=0; $i<$needed; $i++) { $randomDigits .= mt_rand(0,9); }
        $finalEid = '8904' . $randomDigits;
    } else {
        $finalEid = substr($rawEid, 0, 33);
    }
@endphp

<div id="{{ $id }}" class="iphone-screen"
     style="width:375px;height:812px;background:#000;color:{{ $textColor }};
     font-family:-apple-system,BlinkMacSystemFont,sans-serif;
     position:relative;overflow:hidden;padding:2px;box-sizing:border-box;">

    <div style="width:100%;height:100%;background:{{ $bgMain }};
                border-radius:40px;position:relative;overflow:hidden;">

        {{-- STATUS BAR --}}
        <div style="display:flex;justify-content:space-between;
                    padding:14px 26px 0;height:44px;
                    position:absolute;top:0;width:100%;z-index:50;">
            <div style="font-weight:600;font-size:15px;color:{{ $headerColor }};">
                {{ $item['hour'] }}:{{ $item['minute'] }}
            </div>
            <div style="display:flex;align-items:center;gap:6px;">
                <span>{{ $item['batteryLevel'] }}%</span>
            </div>
        </div>

        {{-- BACK LAYER --}}
        <div style="position:absolute;bottom:0;left:50%;
            transform:translateX(-50%);
            width:94%;height:92%;
            background:{{ $bgBack }};
            border-top-left-radius:45px;
            border-top-right-radius:45px;
            z-index:8;"></div>

        {{-- ============================= --}}
        {{-- EXPORT AREA (YANG DI DOWNLOAD) --}}
        {{-- ============================= --}}
        <div class="export-area"
             style="position:absolute;bottom:0;left:0;width:100%;height:90%;
             background:{{ $bgCard }};
             border-top-left-radius:45px;
             border-top-right-radius:45px;
             z-index:10;
             display:flex;
             flex-direction:column;
             overflow:hidden;">

            {{-- HEADER --}}
            <div style="height:50px;padding:0 24px;display:flex;align-items:center;">
                <span style="color:#0A84FF;font-size:18px;">Cancel</span>
            </div>

            {{-- CONTENT --}}
            <div style="flex:1;display:flex;flex-direction:column;
                        align-items:center;margin-top:-10px;">

                <div style="height:50px;margin-bottom:15px;">
                    <svg width="100%" height="50">
                        <text x="50%" y="25" font-size="34"
                              font-weight="700" fill="{{ $textColor }}"
                              text-anchor="middle">Device Info</text>
                    </svg>
                </div>

                @php
                    $fields = [
                        ['label'=>'EID','val'=>$finalEid,'width'=>'92%'],
                        ['label'=>'IMEI','val'=>$item['imei1'],'width'=>'70%'],
                        ['label'=>'IMEI2','val'=>$item['imei2'],'width'=>'70%'],
                        ['label'=>'MEID','val'=>$item['meid'],'width'=>'60%'],
                    ];
                @endphp

                @foreach($fields as $f)
                <div style="margin-bottom:25px;width:100%;text-align:center;">
                    <div style="font-size:13px;margin-bottom:8px;">
                        {{ $f['label'] }} {{ $f['val'] }}
                    </div>
                    <div style="background:#fff;padding:12px 6px;
                                width:{{ $f['width'] }};
                                margin:auto;border-radius:2px;">
                        <svg class="barcode-svg"
                             data-value="{{ $f['val'] }}"
                             data-format="CODE128"
                             data-displayValue="false"
                             style="width:100%;height:40px;"></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- HOME BAR (TIDAK IKUT EXPORT) --}}
        <div style="position:absolute;bottom:8px;left:50%;
            transform:translateX(-50%);
            width:134px;height:5px;
            background:{{ $textColor }};
            border-radius:100px;z-index:20;"></div>
    </div>
</div>

{{-- ===================== --}}
{{-- JAVASCRIPT EXPORT PNG --}}
{{-- ===================== --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function downloadCard(id) {
    const target = document.querySelector(`#${id} .export-area`);
    if (!target) return;

    html2canvas(target, {
        backgroundColor: '#ffffff',
        scale: 2,
        useCORS: true
    }).then(canvas => {
        const a = document.createElement('a');
        a.href = canvas.toDataURL('image/png');
        a.download = id + '.png';
        a.click();
    });
}
</script>

{{-- BUTTON CONTOH --}}
<button onclick="downloadCard('{{ $id }}')">
    Download
</button>
