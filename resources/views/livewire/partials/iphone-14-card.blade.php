@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    $bgMain = $isDark ? '#000000' : '#cccccc';
    $bgCard = $isDark ? '#1c1c1e' : '#ffffff';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000';
    $wifiColor = '#ffffff';
    $signalColor = '#6e6d6d'; // Dikunci abu-abu
    $labelColor = $isDark ? '#8E8E93' : '#6e6e73';
    $closeBtnBg = $isDark ? '#1c1c1e' : '#ffffff';
    $closeIcon = $isDark ? '#ffffff' : '#3c3c43';

    // Logika Fix Pinggiran Putih:
    // Jika Dark Mode, border jadi hitam (#000000) agar pinggiran putih tertutup/invisible.
    // Jika Light Mode, border jadi abu-abu/putih (sesuai selera, di sini diset #d2d2d7).
    $borderColor = $isDark ? '#000000' : '#d2d2d7';

    // Logika Jam & Menit Random Mix
    $randomHour = str_pad(mt_rand(1, 23), 2, '0', STR_PAD_LEFT);
    $randomMinute = str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT);
    $displayHour = $item['hour'] ?? $randomHour;
    $displayMinute = $item['minute'] ?? $randomMinute;

    // Logika Baterai Random & Warna Dinamis
    $currentPercent = $item['batteryLevel'] ?? mt_rand(5, 100);
    $battWidth = ($currentPercent / 100) * 19;
    
    // Penentuan Warna: Merah (Low), Kuning (Hemat/Low), Hijau (Normal)
    if ($currentPercent <= 20) {
        $battFillColor = '#FF3B30'; // Merah
    } elseif ($currentPercent <= 35) {
        $battFillColor = '#FFD60A'; // Kuning (Mode Hemat)
    } else {
        $battFillColor = '#34C759'; // Hijau
    }
    $battTextColor = '#ffffff';

    // Logika WiFi Random dinonaktifkan (WiFi Hilang)
    $showWifi = false; 
    $wifiLevel = 0;

    $randomEid = $item['eid'] ?? '';
    if (strlen($randomEid) < 33) {
        $randomEid =
            '8904' .
            mt_rand(10000000, 99999999) .
            mt_rand(10000000, 99999999) .
            mt_rand(10000000, 99999999) .
            mt_rand(10000, 99999);
    }
    $eidFull = substr($randomEid, 0, 33);
    $eidMain = substr($eidFull, 0, 32);
    $eidLast = substr($eidFull, -1);
@endphp

<div id="{{ $id }}" class="iphone-screen"
    style="width: 375px; height: 812px; background-color: {{ $bgMain }}; border: 4px solid {{ $borderColor }}; color: {{ $textColor }}; font-family: -apple-system, BlinkMacSystemFont, sans-serif; position: relative; overflow: hidden; border-radius: 50px; box-sizing: border-box; -webkit-font-smoothing: antialiased;">
    
    <div
        style="display: flex; justify-content: space-between; padding: 0 26px; align-items: center; position: absolute; top: 11px; left: 0; width: 100%; height: 44px; z-index: 150; box-sizing: border-box;">
        <svg width="60" height="44">
            <text x="5" y="25" font-family="sans-serif" font-size="16" font-weight="600" fill="{{ $headerColor }}"
                letter-spacing="-0.3px">{{ $displayHour }}:{{ $displayMinute }}</text>
        </svg>

        <div style="display: flex; gap: 8px; align-items: center; margin-top: -6px;">
            <div style="display: flex; gap: 2.5px; align-items: center;">
                @for ($i = 1; $i <= 4; $i++)
                    <div
                        style="width: 3.5px; height: 3.5px; background-color: {{ $signalColor }}; border-radius: 50%; opacity: {{ ($item['signalStrength'] ?? 4) >= $i ? '1' : '0.25' }};">
                    </div>
                @endfor
            </div>

            @if ($showWifi && $wifiLevel > 0)
                <svg width="17" height="12" viewBox="0 0 17 12">
                    <path d="M8.5 12L6.5 9.5H10.5L8.5 12Z" fill="{{ $wifiColor }}" />
                    <path opacity="{{ $wifiLevel >= 2 ? '1' : '0.3' }}"
                        d="M8.5 4.5C6.6 4.5 4.9 5.2 3.6 6.4L5 7.8C5.9 7 7.1 6.5 8.5 6.5C9.9 6.5 11.1 7 12 7.8L13.4 6.4C12.1 5.2 10.4 4.5 8.5 4.5Z"
                        fill="{{ $wifiColor }}" />
                    <path opacity="{{ $wifiLevel >= 3 ? '1' : '0.3' }}"
                        d="M8.5 0.5C5.3 0.5 2.3 1.8 0.3 4L1.7 5.4C3.4 3.7 5.8 2.5 8.5 2.5C11.2 2.5 13.6 3.7 15.3 5.4L16.7 4C14.7 1.8 11.7 0.5 8.5 0.5Z"
                        fill="{{ $wifiColor }}" />
                </svg>
            @endif

            <svg width="25" height="13">
                <rect x="0.5" y="1" width="21" height="11" rx="2.5" fill="none"
                    stroke="{{ $headerColor }}" stroke-opacity="0.35" />
                <path d="M22.5 4.5C23.3 4.5 24 5.17 24 6V7.5C24 8.33 23.3 9 22.5 9V4.5Z" fill="{{ $headerColor }}"
                    opacity="0.35" />
                <rect x="1.5" y="2" width="{{ $battWidth }}" height="9" rx="1.5"
                    fill="{{ $battFillColor }}" />
                <text x="11.5" y="7.5" font-family="sans-serif" font-size="7.5" font-weight="800"
                    fill="{{ $battTextColor }}" text-anchor="middle"
                    dominant-baseline="middle">{{ $currentPercent }}</text>
            </svg>
        </div>
    </div>

    <div
        style="position: absolute; bottom: 0; left: 0; width: 100%; height: 92%; background-color: {{ $isDark ? 'rgba(28, 28, 30, 0.95)' : $bgCard }}; border-top-left-radius: 40px; border-top-right-radius: 40px; z-index: 10; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3); backdrop-filter: {{ $isDark ? 'blur(10px)' : 'none' }};">
        <div style="padding: 24px 24px 0 24px; flex-shrink: 0;">
            <div
                style="
    width: 32px; 
    height: 32px; 
    background: {{ $isDark
        ? 'linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.05) 100%)'
        : 'linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(240,240,245,0.8) 100%)' }}; 
    border: 1px solid {{ $isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' }}; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    backdrop-filter: blur(10px); 
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 4px 12px {{ $isDark ? 'rgba(0, 0, 0, 0.4)' : 'rgba(0, 0, 0, 0.12)' }};
    cursor: pointer;
">
                <svg width="12" height="12" viewBox="0 0 16 16">
                    <path d="M12 4L4 12" stroke="{{ $closeIcon }}" stroke-width="2" stroke-linecap="round" />
                    <path d="M4 4L12 12" stroke="{{ $closeIcon }}" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <div style="flex: 1; padding: 0 24px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="text-align: left; padding-top: 5px;">
                <h1
                    style="font-size: 24px; font-weight: 700; color: {{ $textColor }}; letter-spacing: -0.5px; margin: 0 0 8px 0;">
                    Share Device Identifiers</h1>
                <p style="font-size: 16px; color: {{ $labelColor }}; line-height: 1.3; margin: 0 0 15px 0;">
                    You can share EID and IMEIs by<br>
                    scanning the barcodes or<br>
                    holding iPhone near a reader.
                </p>
            </div>

            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; gap: 40px;">
                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <svg width="310" height="55" style="margin-bottom: 12px;">
                        <text x="155" y="12" font-family="sans-serif" font-size="14" font-weight="500"
                            fill="{{ $textColor }}" text-anchor="middle">EID</text>
                        <text x="155" y="38" font-family="sans-serif" font-size="13.5" font-weight="500"
                            fill="{{ $textColor }}" text-anchor="middle">{{ $eidMain }}</text>
                        <text x="155" y="52" font-family="sans-serif" font-size="13.5" font-weight="500"
                            fill="{{ $textColor }}" text-anchor="middle">{{ $eidLast }}</text>
                    </svg>
                    <div
                        style="background-color: #ffffff; padding: 8px 12px; width: 75%; display: flex; justify-content: center; border-radius: 2px;">
                        <svg class="barcode-svg" data-value="{{ $eidFull }}" data-format="CODE128"
                            data-height="55" data-width="1.3" data-displayValue="false" data-margin="0"></svg>
                    </div>
                </div>

                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <svg width="100%" height="28" style="margin-bottom: 18px;">
                        <text x="50%" y="15" font-family="sans-serif" font-size="14" font-weight="500"
                            fill="{{ $textColor }}" text-anchor="middle">IMEI {{ $item['imei1'] ?? '' }}</text>
                    </svg>
                    <div
                        style="background-color: #ffffff; padding: 8px 12px; width: 85%; display: flex; justify-content: center; border-radius: 2px;">
                        <svg class="barcode-svg" data-value="{{ $item['imei1'] ?? '' }}" data-format="CODE128"
                            data-height="55" data-width="2.2" data-displayValue="false" data-margin="0"></svg>
                    </div>
                </div>

                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <svg width="100%" height="28" style="margin-bottom: 18px;">
                        <text x="50%" y="15" font-family="sans-serif" font-size="14" font-weight="500"
                            fill="{{ $textColor }}" text-anchor="middle">IMEI2 {{ $item['imei2'] ?? '' }}</text>
                    </svg>
                    <div
                        style="background-color: #ffffff; padding: 8px 12px; width: 85%; display: flex; justify-content: center; border-radius: 2px;">
                        <svg class="barcode-svg" data-value="{{ $item['imei2'] ?? '' }}" data-format="CODE128"
                            data-height="55" data-width="2.2" data-displayValue="false" data-margin="0"></svg>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 20px 24px 45px 24px;">
            <svg width="327" height="52" style="display: block;">
                <rect width="327" height="52" rx="26" fill="#007AFF" />
                <text x="163.5" y="27" font-family="sans-serif" font-size="17" font-weight="600" fill="#7efdff"
                    text-anchor="middle" dominant-baseline="middle">Use Reader to Share</text>
            </svg>
        </div>
    </div>
</div>