@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    $bgMain = $isDark ? '#000000' : '#f6f8f5';
    $bgCard = $isDark ? '#1c1c1e' : '#ffffff';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000'; 
    $labelColor = $isDark ? '#ffffff' : '#000000';

    $battFillColor = ($item['batteryLevel'] ?? 100) < 20 ? '#FF3B30' : '#34C759';
    $battTextColor = $isDark ? '#ffffff' : '#000000'; 
    
    // Logika EID agar tetap 33 digit
    $rawEid = (string)($item['eid'] ?? '');
    $finalEid = strlen($rawEid) < 33 ? str_pad($rawEid, 33, mt_rand(0,9), STR_PAD_RIGHT) : substr($rawEid, 0, 33);
@endphp

<div class="iphone-chassis" style="
    width: 400px; 
    height: 840px; 
    background: #050505; 
    border-radius: 65px; 
    padding: 12px; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    position: relative; 
    box-shadow: 0 30px 60px rgba(0,0,0,0.6), inset 0 0 5px rgba(255,255,255,0.2);
    border: 1px solid #222;
    margin: 0 auto;
">
    
    <div style="
        position: absolute; 
        top: 35px; 
        width: 60px; 
        height: 6px; 
        background: #1a1a1a; 
        border-radius: 10px; 
        z-index: 100;
        border: 1px solid #111;
    "></div>

    <div id="{{ $id }}" class="iphone-screen" style="
        width: 375px; 
        height: 812px; 
        background-color: {{ $bgMain }}; 
        color: {{ $textColor }}; 
        font-family: -apple-system, BlinkMacSystemFont, sans-serif; 
        position: relative; 
        overflow: hidden; 
        border-radius: 50px; 
        box-sizing: border-box; 
        -webkit-font-smoothing: antialiased;
    ">
        
        <div style="display: flex; justify-content: space-between; padding: 14px 26px 0 26px; align-items: center; height: 44px; position: absolute; top: 0; left: 0; width: 100%; z-index: 50; box-sizing: border-box;">
            <div style="font-weight: 600; font-size: 15px; width: 54px; color: {{ $headerColor }};">
                {{ $item['hour'] }}:{{ $item['minute'] }}
            </div>

            <div style="display: flex; gap: 7px; align-items: center;">
                <div style="display: flex; gap: 2px; align-items: flex-end; height: 12px;">
                    @for($i=1; $i<=4; $i++)
                        <div style="width: 3px; height: {{ $i * 2 + 3 }}px; background-color: {{ $headerColor }}; border-radius: 1px; opacity: {{ ($item['signalStrength'] ?? 4) >= $i ? '1' : '0.3' }};"></div>
                    @endfor
                </div>
                <div style="position: relative; width: 25px; height: 12px;">
                    <svg width="25" height="12" viewBox="0 0 25 12">
                        <rect x="0.5" y="0.5" width="21" height="11" rx="2.5" stroke="{{ $headerColor }}" stroke-width="1" fill="none" opacity="0.35"/>
                        <path d="M22.5 4C23.3 4 24 4.67 24 5.5V6.5C24 7.33 23.3 8 22.5 8V4Z" fill="{{ $headerColor }}" opacity="0.35"/>
                        <rect x="2" y="2" width="{{ ($item['batteryLevel'] / 100) * 18 }}" height="8" rx="1.5" fill="{{ $battFillColor }}"/>
                    </svg>
                </div>
            </div>
        </div>

        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 92%; background-color: {{ $bgCard }}; border-top-left-radius: 40px; border-top-right-radius: 40px; z-index: 10; display: flex; flex-direction: column;">
            
            <div style="height: 60px; padding: 0 24px; display: flex; align-items: center;">
                 <span style="color: #0A84FF; font-size: 17px;">Cancel</span>
            </div>

            <div style="flex-grow: 1; display: flex; flex-direction: column; align-items: center; padding-top: 10px;">
                <h1 style="font-size: 32px; font-weight: 700; margin: 0 0 30px 0; color: {{ $textColor }};">Device Info</h1>

                @php
                    $fields = [
                        ['label' => 'EID', 'val' => $finalEid, 'width' => '90%'],
                        ['label' => 'IMEI', 'val' => $item['imei1'], 'width' => '75%'],
                        ['label' => 'IMEI2', 'val' => $item['imei2'], 'width' => '75%'],
                        ['label' => 'MEID', 'val' => $item['meid'], 'width' => '65%'],
                    ];
                @endphp

                @foreach($fields as $field)
                <div style="margin-bottom: 22px; width: 100%; display: flex; flex-direction: column; align-items: center;">
                    <div style="margin-bottom: 6px; font-size: 13px; font-weight: 500; color: {{ $labelColor }};">
                        {{ $field['label'] }} {{ $field['val'] }}
                    </div>
                    <div style="background: white; padding: 10px; border-radius: 4px; width: {{ $field['width'] }}; display: flex; justify-content: center;">
                        <svg class="barcode-svg" 
                             data-value="{{ $field['val'] }}" 
                             data-format="CODE128" 
                             data-height="45" 
                             data-width="2" 
                             data-displayValue="false" 
                             style="width: 100%; height: 45px;"></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div style="position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); width: 130px; height: 5px; background-color: {{ $textColor }}; border-radius: 10px; z-index: 20;"></div>
    </div>
</div>