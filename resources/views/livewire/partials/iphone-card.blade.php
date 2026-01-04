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

<div id="{{ $id }}" class="iphone-screen" style="width: 375px; height: 812px; background-color: #000000; color: {{ $textColor }}; font-family: -apple-system, BlinkMacSystemFont, sans-serif; position: relative; overflow: hidden; flex-shrink: 0; box-sizing: border-box; -webkit-font-smoothing: antialiased; padding: 2px;">
    
    <div style="width: 100%; height: 100%; background-color: {{ $bgMain }}; border-radius: 40px; position: relative; overflow: hidden;">
        
        <div style="display: flex; justify-content: space-between; padding: 14px 26px 0 26px; align-items: center; height: 44px; position: absolute; top: 0; left: 0; width: 100%; z-index: 50; box-sizing: border-box;">
            <div style="font-weight: 600; font-size: 15px; width: 54px; text-align: left; color: {{ $headerColor }};">
                <svg width="60" height="20">
                    <text x="15" y="15" font-family="sans-serif" font-size="15" font-weight="600" fill="{{ $headerColor }}">{{ $item['hour'] }}:{{ $item['minute'] }}</text>
                </svg>
            </div>

            <div style="display: flex; gap: 7px; align-items: center;">
                <div style="display: flex; gap: 2.5px; align-items: center;">
                    @for($i=1; $i<=4; $i++)
                        <div style="width: 3px; height: 3px; background-color: {{ $headerColor }}; border-radius: 50%; opacity: {{ ($item['signalStrength'] ?? 4) >= $i ? '1' : '0.2' }};"></div>
                    @endfor
                </div>

                <svg width="17" height="12" viewBox="0 0 17 12" fill="{{ $headerColor }}">
                    <path d="M8.5 12L6.5 9.5H10.5L8.5 12Z"/>
                    <path opacity="{{ ($item['wifiLevel'] ?? 3) >= 2 ? '1' : '0.3' }}" d="M8.5 4.5C6.6 4.5 4.9 5.2 3.6 6.4L5 7.8C5.9 7 7.1 6.5 8.5 6.5C9.9 6.5 11.1 7 12 7.8L13.4 6.4C12.1 5.2 10.4 4.5 8.5 4.5Z"/>
                    <path opacity="{{ ($item['wifiLevel'] ?? 3) >= 3 ? '1' : '0.3' }}" d="M8.5 0.5C5.3 0.5 2.3 1.8 0.3 4L1.7 5.4C3.4 3.7 5.8 2.5 8.5 2.5C11.2 2.5 13.6 3.7 15.3 5.4L16.7 4C14.7 1.8 11.7 0.5 8.5 0.5Z"/>
                </svg>

                <div style="position: relative; width: 25px; height: 12px;">
                    <svg width="25" height="12" viewBox="0 0 25 12">
                        <rect x="0.5" y="0.5" width="21" height="11" rx="2.5" stroke="{{ $headerColor }}" stroke-width="1" fill="none" opacity="0.35"/>
                        <path d="M22.5 4C23.3 4 24 4.67 24 5.5V6.5C24 7.33 23.3 8 22.5 8V4Z" fill="{{ $headerColor }}" opacity="0.35"/>
                        <rect x="2" y="2" width="{{ $item['battWidth'] ?? 19 }}" height="8" rx="1.5" fill="{{ $battFillColor }}"/>
                        <text x="11" y="6.5" font-family="sans-serif" font-size="6.5" font-weight="700" fill="{{ $battTextColor }}" text-anchor="middle" dominant-baseline="middle">{{ $item['batteryLevel'] }}</text>
                    </svg>
                </div>
            </div>
        </div>

        <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 94%; height: 92%; background-color: {{ $bgBack }}; border-top-left-radius: 45px; border-top-right-radius: 45px; z-index: 8;"></div>

        <div class="export-area" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 90%; background-color: {{ $bgCard }}; border-top-left-radius: 45px; border-top-right-radius: 45px; z-index: 10; display: flex; flex-direction: column; overflow: hidden;">
            {{-- ISI ANDA TETAP --}}
        </div>

        <div style="position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); width: 134px; height: 5px; background-color: {{ $textColor }}; border-radius: 100px; z-index: 20; opacity: 1;"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function downloadCard(id) {
    const target = document.querySelector('#' + id + ' .export-area');
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
