<div class="info-box bg-green-gradient">
    <a href="/transparencia/contratos/?{{ $url_filtro }}"
       style="color: inherit;" title="Consultar Total Contratos">
        <span class="info-box-icon"><i class="ion ion-ios-list-outline"></i></span>
    </a>

    <div class="info-box-content">
        <span class="info-box-text">Total Contratos</span>
        <span class="info-box-number">{{ ($data['contratos_total_numero']) ?? '0' }}</span>

        <div class="progress">
            <div class="progress-bar"
                 style="width: {{ ($data['contratos_total_percentual']) ?? '0%' }}"></div>
        </div>
        <span class="progress-description">
            {{ ($data['contratos_total_percentual']) ?? '0%' }} Contratos ativos
        </span>
    </div>
</div>

<div class="info-box bg-red-gradient">
    <a href="/transparencia/contratos/?{{ $url_filtro }}vigencia_fim={{ $url_datas['dt30'] }}"
       style="color: inherit;" title="Consultar Vencem (-30 dias)">
        <span class="info-box-icon">
            <i class="ion ion-ios-copy-outline"></i>
        </span>
    </a>

    <div class="info-box-content">
        <span class="info-box-text">Vencem (- 30 dias)</span>
        <span class="info-box-number">{{ ($data['contratos_vencer30_numero']) ?? '0' }}</span>

        <div class="progress">
            <div class="progress-bar"
                 style="width: {{ ($data['contratos_vencer30_percentual']) ?? '0%' }}"></div>
        </div>
        <span class="progress-description">
            {{ ($data['contratos_vencer30_percentual']) ?? '0%' }} à vencer (- 30 Dias)
      </span>
    </div>
</div>

<div class="info-box bg-orange-active">
    <a href="/transparencia/contratos/?{{ $url_filtro }}vigencia_fim={{ $url_datas['dt3060'] }}"
       style="color: inherit;" title="Consultar Vencem (60 a 60 dias)">
        <span class="info-box-icon"><i class="ion ion-ios-copy-outline"></i></span>
    </a>

    <div class="info-box-content">
        <span class="info-box-text">Vencem (30 a 60 dias)</span>
        <span class="info-box-number">{{ ($data['contratos_vencer3060_numero']) ?? '0' }}</span>

        <div class="progress">
            <div class="progress-bar"
                 style="width: {{ ($data['contratos_vencer3060_percentual']) ?? '0%' }}"></div>
        </div>
        <span class="progress-description">
            {{ ($data['contratos_vencer3060_percentual']) ?? '0%' }} à vencer (30 a 60 Dias)
        </span>
    </div>
</div>

<div class="info-box bg-yellow-gradient">
    <a href="/transparencia/contratos/?{{ $url_filtro }}vigencia_fim={{ $url_datas['dt6090'] }}"
       style="color: inherit;" title="Consultar Vencem (60 a 90 dias)">
        <span class="info-box-icon"><i class="ion-ios-copy-outline"></i></span>
    </a>

    <div class="info-box-content">
        <span class="info-box-text">Vencem (60 a 90 dias)</span>
        <span class="info-box-number">{{ ($data['contratos_vencer6090_numero']) ?? '0' }}</span>

        <div class="progress">
            <div class="progress-bar"
                 style="width: {{ ($data['contratos_vencer6090_percentual']) ?? '0%' }}"></div>
        </div>
        <span class="progress-description">
            {{ ($data['contratos_vencer6090_percentual']) ?? '0%' }} à vencer (60 a 90 Dias)
        </span>
    </div>
</div>

<div class="info-box bg-aqua-gradient">
    <a href="/transparencia/contratos/?{{ $url_filtro }}vigencia_fim={{ $url_datas['dt90180'] }}"
       style="color: inherit;" title="Consultar Vencem (90 a 180 dias)">
        <span class="info-box-icon"><i class="ion ion-ios-copy-outline"></i></span>
    </a>

    <div class="info-box-content">
        <span class="info-box-text">Vencem (90 a 180 dias)</span>
        <span class="info-box-number">{{ ($data['contratos_vencer90180_numero']) ?? '0' }}</span>

        <div class="progress">
            <div class="progress-bar"
                 style="width: {{ ($data['contratos_vencer90180_percentual']) ?? '0%' }}"></div>
        </div>
        <span class="progress-description">
            {{ ($data['contratos_vencer90180_percentual']) ?? '0%' }} à vencer (90 a 180 Dias)
        </span>
    </div>
</div>

<div class="info-box bg-blue-gradient">
    <a href="/transparencia/contratos/?{{ $url_filtro }}vigencia_fim={{ $url_datas['dt180'] }}" style="color: inherit;" title="Consultar Vencem (+180 dias)">
        <span class="info-box-icon"><i class="ion ion-ios-copy-outline"></i></span>
    </a>

    <div class="info-box-content">
        <span class="info-box-text">Vencem (+ 180 dias)</span>
        <span class="info-box-number">{{ ($data['contratos_vencer180_numero']) ?? '0' }}</span>

        <div class="progress">
            <div class="progress-bar"
                 style="width: {{ ($data['contratos_vencer180_percentual']) ?? '0%' }}"></div>
        </div>
        <span class="progress-description">
            {{ ($data['contratos_vencer180_percentual']) ?? '0%' }} à vencer (+ 180 Dias)
        </span>
    </div>
</div>
