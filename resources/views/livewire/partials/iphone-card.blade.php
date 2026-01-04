@php
    // Force white background only
    $bgColor = '#ffffff';
    $textColor = '#000000';
    
    $battFillColor = ($item['batteryLevel'] ?? 100) < 20 ? '#FF3B30' : '#34C759';
    
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

<div id="{{ $id }}" style="width: 375px; height: 812px; background-color: {{ $bgColor }}; color: {{ $textColor }}; font-family: -apple-system, BlinkMacSystemFont, sans-serif; margin: 0; padding: 0; position: relative;">

    <!-- Status Bar - Simplified -->
    <div style="padding: 18px 24px 0 24px; height: 44px; display: flex; justify-content: space-between; align-items: center; background-color: {{ $bgColor }};">
        <!-- Time -->
        <div style="font-weight: 600; font-size: 15px; color: {{ $textColor }};">
            {{ $item['hour'] }}:{{ $item['minute'] }}
        </div>
        
        <!-- Status Icons -->
        <div style="display: flex; align-items: center; gap: 7px;">
            <!-- Signal Dots -->
            <div style="display: flex; gap: 2.5px;">
                @for($i=1; $i<=4; $i++)
                    <div style="width: 3px; height: 3px; background-color: {{ $textColor }}; border-radius: 50%; opacity: {{ ($item['signalStrength'] ?? 4) >= $i ? '1' : '0.2' }};"></div>
                @endfor
            </div>
            
            <!-- Battery - Simplified -->
            <div style="position: relative; width: 25px; height: 12px;">
                <svg width="25" height="12" viewBox="0 0 25 12">
                    <rect x="2" y="2" width="{{ $item['battWidth'] ?? 19 }}" height="8" rx="1.5" fill="{{ $battFillColor }}"/>
                    <text x="11" y="6.5" font-family="sans-serif" font-size="6.5" font-weight="700" fill="{{ $textColor }}" text-anchor="middle" dominant-baseline="middle">{{ $item['batteryLevel'] }}</text>
                </svg>
            </div>
        </div>
    </div>

    <!-- Cancel Button -->
    <div style="padding: 10px 24px 20px 24px; background-color: {{ $bgColor }};">
        <span style="color: #0A84FF; font-size: 18px; font-weight: 400;">Cancel</span>
    </div>

    <!-- Title -->
    <div style="padding: 0 24px 30px 24px; background-color: {{ $bgColor }}; text-align: center;">
        <div style="font-size: 34px; font-weight: 700; color: {{ $textColor }};">Device Info</div>
    </div>

    @php
        $fields = [
            ['label' => 'EID', 'val' => $finalEid, 'width' => '92%'],
            ['label' => 'IMEI', 'val' => $item['imei1'], 'width' => '72%'],
            ['label' => 'IMEI2', 'val' => $item['imei2'], 'width' => '72%'],
            ['label' => 'MEID', 'val' => $item['meid'], 'width' => '62%'],
        ];
    @endphp

    <!-- Device Info Items -->
    <div style="padding: 0 24px; background-color: {{ $bgColor }};">
        @foreach($fields as $field)
        <div style="margin-bottom: 30px; text-align: center;">
            <!-- Label -->
            <div style="font-size: 13px; font-weight: 500; color: {{ $textColor }}; margin-bottom: 8px;">
                {{ $field['label'] }} {{ $field['val'] }}
            </div>
            
            <!-- Barcode Box -->
            <div style="background-color: #ffffff; padding: 12px 6px; width: {{ $field['width'] }}; margin: 0 auto; border-radius: 2px; border: 1px solid #e0e0e0;">
                <svg class="barcode-svg" 
                     data-value="{{ $field['val'] }}" 
                     data-format="CODE128" 
                     data-height="50" 
                     data-width="4" 
                     data-displayValue="false" 
                     data-margin="0"
                     preserveAspectRatio="none"
                     style="width: 100%; height: 20px;"></svg>
            </div>
        </div>
        @endforeach
    </div>
</div>