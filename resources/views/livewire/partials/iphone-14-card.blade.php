@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    
    // Warna Body HP (Bezel)
    $bgMain = $isDark ? '#000000' : '#d1d1d6'; 
    
    // Warna Layar/Card
    $bgCard = $isDark ? '#1c1c1e' : '#ffffff';
    
    // Warna Teks
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000';
    
    // Indikator
    $wifiColor = $isDark ? '#ffffff' : '#000000';
    $signalColor = $isDark ? '#ffffff' : '#000000';
    
    $labelColor = $isDark ? '#8E8E93' : '#6e6e73';
    $closeBtnBg = $isDark ? '#2c2c2e' : '#e5e5ea';
    $closeIcon = $isDark ? '#ffffff' : '#3c3c43';

    // FIX PINGGIRAN: 
    // Border dibuat sama persis dengan warna background ($bgMain) 
    // agar menyatu dan menutup pixel putih (aliasing).
    $borderColor = $bgMain; 

    // Logika Jam
    $randomHour = str_pad(mt_rand(1, 23), 2, '0', STR_PAD_LEFT);
    $randomMinute = str_pad(mt_rand(0, 59), 2, '0', STR_PAD_LEFT);
    $displayHour = $item['hour'] ?? $randomHour;
    $displayMinute = $item['minute'] ?? $randomMinute;

    // Logika Baterai
    $currentPercent = $item['batteryLevel'] ?? mt_rand(20, 95);
    $battWidth = ($currentPercent / 100) * 19;
    
    // Warna Baterai
    if ($currentPercent <= 20) {
        $battFillColor = '#FF3B30'; // Merah
    } elseif ($currentPercent <= 40) {
        $battFillColor = '#FFD60A'; // Kuning
    } else {
        $battFillColor = $isDark ? '#32D74B' : '#34C759'; // Hijau
    }
    $battTextColor = $isDark ? '#000000' : '#ffffff'; 

    // EID Logic
    $randomEid = $item['eid'] ?? '';
    if (strlen($randomEid) < 33) {
        $randomEid = '8904' . mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999) . mt_rand(10000000, 99999999) . mt_rand(10000, 99999);
    }
    $eidFull = substr($randomEid, 0, 33);
    $eidMain = substr($eidFull, 0, 32);
    $eidLast = substr($eidFull, -1);
@endphp

<div id="{{ $id }}" class="iphone-screen"
    style="
        width: 375px; 
        height: 812px; 
        background-color: {{ $bgMain }}; 
        border: 6px solid {{ $borderColor }}; /* Border tebal untuk menutup gerigi pixel */
        color: {{ $textColor }}; 
        font-family: -apple-system, BlinkMacSystemFont, sans-serif; 
        position: relative; 
        overflow: hidden; 
        border-radius: 56px; /* Radius diperhalus */
        box-sizing: border-box; 
        -webkit-font-smoothing: antialiased;
        box-shadow: inset 0 0 0 2px {{ $isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' }}; /* Inner shadow halus */
    ">
    
    <div style="display: flex; justify-content: space-between; padding: 0 26px; align-items: center; position: absolute; top: 14px; left: 0; width: 100%; height: 44px; z-index: 150; box-sizing: border-box;">
        
        <div style="width: 60px;">
            <span style="font-size: 17px; font-weight: 600; color: {{ $headerColor }}; letter-spacing: -0.5px;">
                {{ $displayHour }}:{{ $displayMinute }}
            </span>
        </div>

        <div style="display: flex; gap: 6px; align-items: center;">
            <div style="display: flex; gap: 2px; align-items: flex-end; height: 12px;">
                @for ($i = 1; $i <= 4; $i++)
                    <div style="
                        width: 4px; 
                        height: {{ 4 + ($i * 2) }}px; 
                        background-color: {{ $signalColor }}; 
                        border-radius: 1px; 
                        opacity: {{ ($item['signalStrength'] ?? 4) >= $i ? '1' : '0.3' }};">
                    </div>
                @endfor
            </div>

            {{-- <svg width="18" height="12" viewBox="0 0 17 12"><path d="..." fill="{{ $wifiColor }}"/></svg> --}}

            <div style="position: relative; width: 25px; height: 13px;">
                <div style="
                    position: absolute; left: 0; top: 0;
                    width: 22px; height: 13px;
                    border: 1px solid {{ $isDark ? 'rgba(255,255,255,0.4)' : 'rgba(0,0,0,0.4)' }};
                    border-radius: 4px;
                "></div>
                <div style="
                    position: absolute; left: 2px; top: 2px;
                    width: {{ $battWidth }}px; height: 9px;
                    background-color: {{ $battFillColor }};
                    border-radius: 2px;
                "></div>
                <div style="
                    position: absolute; right: 0; top: 4px;
                    width: 3px; height: 5px;
                    background-color: {{ $isDark ? 'rgba(255,255,255,0.4)' : 'rgba(0,0,0,0.4)' }};
                    border-top-right-radius: 2px;
                    border-bottom-right-radius: 2px;
                "></div>
            </div>
        </div>
    </div>

    <div style="
        position: absolute; 
        bottom: 0; left: 0; 
        width: 100%; height: 91%; 
        background-color: {{ $bgCard }}; 
        border-top-left-radius: 44px; 
        border-top-right-radius: 44px; 
        z-index: 10; 
        display: flex; 
        flex-direction: column; 
        overflow: hidden; 
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.2);
    ">
        
        <div style="padding: 22px 24px 0 24px; flex-shrink: 0; display: flex; justify-content: flex-end;">
            <div style="
                width: 30px; height: 30px; 
                background-color: {{ $closeBtnBg }}; 
                border-radius: 50%; 
                display: flex; align-items: center; justify-content: center;
                cursor: pointer;
            ">
                <svg width="10" height="10" viewBox="0 0 14 14" style="opacity: 0.7;">
                    <path d="M13 1L1 13M1 1L13 13" stroke="{{ $closeIcon }}" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <div style="flex: 1; padding: 0 24px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="text-align: left; margin-top: 0px;">
                <h1 style="font-size: 26px; font-weight: 700; color: {{ $textColor }}; margin: 0 0 10px 0; letter-spacing: -0.4px;">
                    Share Device Identifiers
                </h1>
                <p style="font-size: 16px; color: {{ $labelColor }}; line-height: 1.4; margin: 0;">
                    You can share EID and IMEIs by<br>
                    scanning the barcodes or<br>
                    holding iPhone near a reader.
                </p>
            </div>

            <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; gap: 35px; padding: 20px 0;">
                
                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <div style="text-align: center; margin-bottom: 8px;">
                        <div style="font-size: 13px; font-weight: 600; color: {{ $labelColor }}; margin-bottom: 4px;">EID</div>
                        <div style="font-size: 13px; font-weight: 500; color: {{ $textColor }}; letter-spacing: 0.5px;">{{ $eidMain }}</div>
                        <div style="font-size: 13px; font-weight: 500; color: {{ $textColor }}; letter-spacing: 0.5px;">{{ $eidLast }}</div>
                    </div>
                    <div style="background-color: #ffffff; padding: 10px; border-radius: 8px; width: 80%; display: flex; justify-content: center;">
                        <svg class="barcode-svg" 
                             data-value="{{ $eidFull }}" 
                             data-format="CODE128"
                             data-height="45" 
                             data-width="1.2" 
                             data-displayValue="false" 
                             data-margin="0">
                        </svg>
                    </div>
                </div>

                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <div style="font-size: 13px; font-weight: 500; color: {{ $labelColor }}; margin-bottom: 8px;">
                        IMEI <span style="color: {{ $textColor }}">{{ $item['imei1'] ?? '' }}</span>
                    </div>
                    <div style="background-color: #ffffff; padding: 10px; border-radius: 8px; width: 90%; display: flex; justify-content: center;">
                        <svg class="barcode-svg" 
                             data-value="{{ $item['imei1'] ?? '' }}" 
                             data-format="CODE128"
                             data-height="45" 
                             data-width="2.1" 
                             data-displayValue="false" 
                             data-margin="0">
                        </svg>
                    </div>
                </div>

                <div style="width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <div style="font-size: 13px; font-weight: 500; color: {{ $labelColor }}; margin-bottom: 8px;">
                        IMEI2 <span style="color: {{ $textColor }}">{{ $item['imei2'] ?? '' }}</span>
                    </div>
                    <div style="background-color: #ffffff; padding: 10px; border-radius: 8px; width: 90%; display: flex; justify-content: center;">
                        <svg class="barcode-svg" 
                             data-value="{{ $item['imei2'] ?? '' }}" 
                             data-format="CODE128"
                             data-height="45" 
                             data-width="2.1" 
                             data-displayValue="false" 
                             data-margin="0">
                        </svg>
                    </div>
                </div>

            </div>
        </div>

        <div style="padding: 10px 24px 50px 24px;">
            <div style="
                width: 100%; height: 50px; 
                background-color: #007AFF; 
                border-radius: 25px; 
                display: flex; align-items: center; justify-content: center;
            ">
                <span style="font-size: 17px; font-weight: 600; color: #ffffff;">Use Reader to Share</span>
            </div>
        </div>
    </div>
</div>