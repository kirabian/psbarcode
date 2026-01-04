@php
    $isDark = false; // Force light mode
    $bgColor = '#ffffff'; // Background putih
    $bgBack = '#ffffff'; // Background putih untuk bagian atas
    $textColor = '#000000'; // Teks hitam
    $headerColor = '#000000'; 
    $labelColor = '#000000';

    $battFillColor = ($item['batteryLevel'] ?? 100) < 20 ? '#FF3B30' : '#34C759';
    $battTextColor = '#000000'; 
    
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

<div id="{{ $id }}" class="iphone-screen-content" style="width: 375px; height: 812px; background-color: {{ $bgColor }}; color: {{ $textColor }}; font-family: -apple-system, BlinkMacSystemFont, sans-serif; position: relative; overflow: visible; box-sizing: border-box; -webkit-font-smoothing: antialiased; margin: 0; padding: 0;">
    
    <!-- Container untuk semua konten -->
    <div style="width: 100%; height: 100%; background-color: {{ $bgColor }}; position: absolute; top: 0; left: 0; z-index: 10;">
        
        <!-- Status Bar -->
        <div style="display: flex; justify-content: space-between; padding: 18px 24px 0 24px; align-items: center; height: 44px; width: 100%; box-sizing: border-box; background-color: {{ $bgColor }};">
            <div style="font-weight: 600; font-size: 15px; width: 54px; text-align: left; color: {{ $headerColor }};">
                {{ $item['hour'] }}:{{ $item['minute'] }}
            </div>

            <div style="display: flex; gap: 7px; align-items: center;">
                <!-- Signal Dots -->
                <div style="display: flex; gap: 2.5px; align-items: center;">
                    @for($i=1; $i<=4; $i++)
                        <div style="width: 3px; height: 3px; background-color: {{ $headerColor }}; border-radius: 50%; opacity: {{ ($item['signalStrength'] ?? 4) >= $i ? '1' : '0.2' }};"></div>
                    @endfor
                </div>

                <!-- WiFi Icon (simplified without black borders) -->
                <div style="width: 17px; height: 12px; position: relative;">
                    <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-bottom: 6px solid {{ $headerColor }};"></div>
                </div>

                <!-- Battery Icon (simplified without black border) -->
                <div style="position: relative; width: 25px; height: 12px; background-color: transparent;">
                    <div style="position: absolute; top: 2px; left: 2px; width: {{ $item['battWidth'] ?? 19 }}px; height: 8px; background-color: {{ $battFillColor }}; border-radius: 1.5px;"></div>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 6.5px; font-weight: 700; color: {{ $battTextColor }}; z-index: 2;">
                        {{ $item['batteryLevel'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Button -->
        <div style="height: 50px; padding: 0 24px; display: flex; align-items: center; background-color: {{ $bgColor }}; margin-top: 10px;">
             <span style="color: #0A84FF; font-size: 18px; font-weight: 400;">Cancel</span>
        </div>

        <!-- Main Content -->
        <div style="flex-grow: 1; display: flex; flex-direction: column; align-items: center; overflow: hidden; padding-top: 20px; background-color: {{ $bgColor }};">
            <!-- Title -->
            <div style="width: 100%; height: 50px; margin-bottom: 25px; text-align: center;">
                <div style="font-size: 34px; font-weight: 700; color: {{ $textColor }};">Device Info</div>
            </div>

            @php
                $fields = [
                    ['label' => 'EID', 'val' => $finalEid, 'width' => '92%', 'barHeight' => 20],
                    ['label' => 'IMEI', 'val' => $item['imei1'], 'width' => '72%', 'barHeight' => 20],
                    ['label' => 'IMEI2', 'val' => $item['imei2'], 'width' => '72%', 'barHeight' => 20],
                    ['label' => 'MEID', 'val' => $item['meid'], 'width' => '62%', 'barHeight' => 20],
                ];
            @endphp

            @foreach($fields as $field)
            <div style="margin-bottom: 30px; width: 100%; display: flex; flex-direction: column; align-items: center; flex-shrink: 0;">
                <!-- Label -->
                <div style="width: 100%; height: 20px; margin-bottom: 8px; text-align: center;">
                    <div style="font-size: 13px; font-weight: 500; color: {{ $labelColor }};">
                        {{ $field['label'] }} {{ $field['val'] }}
                    </div>
                </div>

                <!-- Barcode Container -->
                <div style="background-color: #ffffff; padding: 12px 6px; width: {{ $field['width'] }}; height: auto; display: flex; justify-content: center; align-items: center; border-radius: 2px; overflow: hidden; box-sizing: border-box; border: 1px solid #e0e0e0;">
                    <svg class="barcode-svg" 
                         data-value="{{ $field['val'] }}" 
                         data-format="CODE128" 
                         data-height="50" 
                         data-width="4" 
                         data-displayValue="false" 
                         data-margin="0"
                         preserveAspectRatio="none"
                         style="width: 100%; height: {{ $field['barHeight'] }}px;"></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>