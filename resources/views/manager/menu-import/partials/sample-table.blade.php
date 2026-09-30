<div class="mimp-sample-wrap">
    <table class="mimp-sample">
        <thead>
            <tr>
                @foreach(\App\Services\MenuImport\MenuImportFormat::HEADERS as $header)
                    <th scope="col">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach(\App\Services\MenuImport\MenuImportFormat::SAMPLE_ROWS as $sampleRow)
                <tr>
                    @foreach($sampleRow as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
