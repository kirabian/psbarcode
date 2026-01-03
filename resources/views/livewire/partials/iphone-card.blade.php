@php
    $isDark = ($item['theme'] ?? 'light') == 'dark';
    $bgMain = $isDark ? '#000000' : '#f6f8f5';
    $bgCard = $isDark ? '#1c1c1e' : '#ffffff';
    $bgBack = $isDark ? '#141613' : '#d6d6d6';
    $textColor = $isDark ? '#ffffff' : '#000000';
    $headerColor = $isDark ? '#ffffff' : '#000000'; 
    $labelColor = $isDark ? '#8E8E93' : '#6e6e73';
    $boxBg = '#ffffff';

    if (($item['batteryLevel'] ?? 100) < 20) {
        $battFillColor = '#FFD60A'; 
    } else {
        $battFillColor = '#34C759'; 
    }
    
    $battTextColor = $item['battTextRandom'] ?? ($isDark ? '#000000' : '#FFFFFF'); 
@endphp

<div id="{{ $id }}" class="iphone-screen" style="width: 375px; height: 812px; background-color: {{ $bgMain }}; color: {{ $textColor }}; font-family: -apple-system, BlinkMacSystemFont, sans-serif; position: relative; overflow: hidden; flex-shrink: 0; box-sizing: border-box; -webkit-font-smoothing: antialiased;">
    
    <div style="display: flex; justify-content: space-between; padding: 14px 26px 0 26px; align-items: center; height: 44px; position: absolute; top: 0; left: 0; width: 100%; z-index: 50; box-sizing: border-box;">
        <div style="font-weight: 600; font-size: 15px; width: 54px; text-align: left; color: {{ $headerColor }};">
            {{ $item['hour'] }}:{{ $item['minute'] }}
        </div>

        <div style="display: flex; gap: 7px; align-items: center;">
            <div style="display: flex; gap: 1px; align-items: center; margin-right: 2px;">
                @for($i=0; $i<4; $i++)
                    <div style="width: 5px; height: 5px; background-color: #8E8E93; opacity: 0.2; border-radius: 50%;"></div>
                @endfor
            </div>

            <svg width="17" height="12" viewBox="0 0 17 12" fill="{{ $headerColor }}">
                <path d="M8.5 12L6.5 9.5H10.5L8.5 12Z"/>
                <path opacity="{{ ($item['wifiLevel'] ?? 3) >= 2 ? '1' : '0.3' }}" d="M8.5 4.5C6.6 4.5 4.9 5.2 3.6 6.4L5 7.8C5.9 7 7.1 6.5 8.5 6.5C9.9 6.5 11.1 7 12 7.8L13.4 6.4C12.1 5.2 10.4 4.5 8.5 4.5Z"/>
                <path opacity="{{ ($item['wifiLevel'] ?? 3) >= 3 ? '1' : '0.3' }}" d="M8.5 0.5C5.3 0.5 2.3 1.8 0.3 4L1.7 5.4C3.4 3.7 5.8 2.5 8.5 2.5C11.2 2.5 13.6 3.7 15.3 5.4L16.7 4C14.7 1.8 11.7 0.5 8.5 0.5Z"/>
            </svg>

            <div style="position: relative; width: 25px; height: 12px; margin-left: 1px;">
                <svg width="25" height="12" viewBox="0 0 25 12" style="display: block;">
                    <rect x="0.5" y="0.5" width="21" height="11" rx="2.5" stroke="{{ $headerColor }}" stroke-width="1" fill="none" opacity="0.35"/>
                    <path d="M22.5 4C23.3 4 24 4.67 24 5.5V6.5C24 7.33 23.3 8 22.5 8V4Z" fill="{{ $headerColor }}" opacity="0.35"/>
                    <rect x="2" y="2" width="{{ $item['battWidth'] ?? 19 }}" height="8" rx="1.5" fill="{{ $battFillColor }}"/>
                    
                    @if($item['showPercentage'] ?? true)
                    <text x="11" y="6.5" font-family="sans-serif" font-size="6.5" font-weight="700" fill="{{ $battTextColor }}" text-anchor="middle" dominant-baseline="middle">
                        {{ $item['batteryLevel'] }}
                    </text>
                    @endif
                </svg>
            </div>
        </div>
    </div>

    <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 94%; height: 92%; background-color: {{ $bgBack }}; border-top-left-radius: 14px; border-top-right-radius: 14px; z-index: 8;"></div>

    <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 90%; background-color: {{ $bgCard }}; border-top-left-radius: 14px; border-top-right-radius: 14px; z-index: 10; display: flex; flex-direction: column; overflow: hidden;">
        
        <div style="height: 50px; padding: 0 20px; display: flex; align-items: center; flex-shrink: 0;">
             <span style="color: #0A84FF; font-size: 17px; font-weight: 400;">Cancel</span>
        </div>

        <div style="flex-grow: 1; display: flex; flex-direction: column; align-items: center; overflow: hidden;">
            
            <div style="width: 100%; height: 60px; flex-shrink: 0;">
                <svg width="100%" height="60" viewBox="0 0 375 60">
                    <text x="50%" y="30" font-family="sans-serif" font-size="34" font-weight="700" fill="{{ $textColor }}" text-anchor="middle" dominant-baseline="middle">
                        Device Info
                    </text>
                </svg>
            </div>

            @php
                $fields = [
                    ['label' => 'EID', 'key' => 'eid', 'width' => '92%', 'height' => 75, 'scale' => 2.2],
                    ['label' => 'IMEI', 'key' => 'imei1', 'width' => '68%', 'height' => 65, 'scale' => 2.8],
                    ['label' => 'IMEI2', 'key' => 'imei2', 'width' => '68%', 'height' => 65, 'scale' => 2.8],
                    ['label' => 'MEID', 'key' => 'meid', 'width' => '68%', 'height' => 65, 'scale' => 2.8],
                ];
            @endphp

            @foreach($fields as $field)
            <div style="margin-bottom: 20px; width: 100%; display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                
                <div style="width: 100%; height: 25px;">
                    <svg width="100%" height="25" viewBox="0 0 375 25">
                        <text x="50%" y="12" font-family="sans-serif" font-size="13" fill="{{ $labelColor }}" text-anchor="middle" dominant-baseline="middle">
                            {{ $field['label'] }} {{ $item[$field['key']] }}
                        </text>
                    </svg>
                </div>

                <div style="background-color: #ffffff; padding: 10px 4px; width: {{ $field['width'] }}; display: flex; justify-content: center; border-radius: 4px;">
                    <svg class="barcode-svg" data-value="{{ $item[$field['key']] }}" data-format="CODE128" data-height="{{ $field['height'] }}" data-width="{{ $field['scale'] }}" data-displayValue="false" data-margin="0"></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <div style="position: absolute; bottom: 8px; left: 50%; transform: translateX(-50%); width: 134px; height: 5px; background-color: {{ $headerColor }}; border-radius: 100px; z-index: 20; opacity: 0.8;"></div>
</div>