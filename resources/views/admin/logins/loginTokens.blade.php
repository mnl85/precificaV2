<x-page-template bodyClass='g-sidenav-show bg-gray-200'>
    <x-auth.navbars.sidebar activePage="admin" activeItem="logins" activeSubitem="loginsTokens">
    </x-auth.navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar Route Titles -->
        <x-auth.navbars.navs.auth 
        pageTitle="Admin" 
        itemTitle="Logins" 
        subItemTitle="Tokens para Login" 
        >
        </x-auth.navbars.navs.auth>
        <!-- End Navbar Route Titles -->
        <style>
            .table-responsive {
                overflow-x: auto;
            }
            #dataTable {
                max-width: 800px;
                margin: 0 auto;
            }

            .fs-custom {
                font-size: 0.75rem; /* Ajuste conforme necessário */
            }
 
            .custom-card-width1 {
                width: 32%;
                max-width: 400px;
                margin-left: 0;
            }
            .custom-card-width2 {
                width: 50%;
                max-width: 600px;
                margin-left: 0;
            }
        </style>
        @php
            $nextHour = $createdAt->copy()->addHour()->startOfHour();
            $now = \Carbon\Carbon::now();
            $timeToNextHour = $nextHour->diffInSeconds($now);

            $minutes = floor($timeToNextHour / 60);
            $seconds = $timeToNextHour % 60;

            $totalSeconds = $timeToNextHour;
        @endphp
        @php
                            function formatarDuracao($segundos) {
                                $minutos = floor($segundos / 60);
                                return "$minutos min";
                            }

                            $duracaoTotal = 0;
                            function formatDateTime($createdAt) {
                                            date_default_timezone_set('America/Sao_Paulo');
                                            $dateTime = new DateTime($createdAt); 
                                            #$dateTime->modify('-3 hours');
                                            $now = new DateTime();
                                            if ($dateTime->format('Y-m-d') === $now->format('Y-m-d')) {
                                                return $dateTime->format('H:i');
                                            } else {
                                                return $dateTime->format('d/m/y');
                                            }
                                        }
                        @endphp
    <div class="container-fluid py-4">
        <!-- CABEÇALHO INICIAL inicio -->
        <div class="card  p-4">
            <h5 class="mb-0">Tokens para Login</h5>
            <!-- <div class="flex justify-between pt-4">
         
                <button type="button" class="btn btn-block bg-gradient-primary mb-3" data-bs-toggle="modal" data-bs-target="#modal-form">Novo Trabalho Base</button>
            </div> -->
        </div>
        <!-- CABEÇALHO INICIAL fim -->
        @if (Session::has('status'))
        <div class="alert alert-success alert-dismissible text-white mx-4" role="alert">
            <span class="text-sm">{{ Session::get('status') }}</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="card custom-card-width1 my-4 p-4">
            <div>
                <h2>Token Atual: <span style="font-size: 28px;"><b>{{ $tokenValue }}</b></span></h2><br>
                <p style="font-size: 12px;">Criado em: {{($createdAt)->sub(new DateInterval('PT3H'))}}</p>
                <p style="font-size: 12px;">Expira em: <span id="countdown">{{ sprintf('%02d:%02d', $minutes, $seconds) }}</span></p>
                <p style="font-size: 12px;">obs: o token expira a cada virada de hora e ao ser utilizado</p>
            </div>
            <div class="p-2">
                @if (session('message'))
                    <div id="success-message" class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                <form action="{{ route('generate.token') }}" method="GET" class="text-center">
                    @csrf
                    <button type="submit" class="btn btn-primary">Gerar Novo Token</button>
                </form>
            </div>
        </div>
        
        <div class="card custom-card-width2 my-4 p-4">
            <div class="table-responsive">
                <h3 class="">Listagem gerados na última semana</h3>
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                ID</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Token</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                Criado em</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($listaTokens as $lt)
                        <tr>
                            <td class="text-sm font-weight-normal">{{ $lt['id_val_token'] }}</td>
                            <td class="text-sm font-weight-normal">{{ $lt['token'] }}</td>
                            <td class="text-sm font-weight-normal">{{ formatDateTime(($lt['created_at'])->sub(new DateInterval('PT3H'))) }}</td>                          
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    

        <x-auth.footers.auth.footer></x-auth.footers.auth.footer>
    </div>

    </main>
    <x-plugins></x-plugins>
    @push('js')
    <script>
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 10000); // 10 segundos (10000 milissegundos)

        let totalSeconds = {{ $totalSeconds }};
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            if (totalSeconds <= 0) {
                countdownElement.textContent = "00:00";
                return;
            }

            totalSeconds--;

            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

            countdownElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
    </script>
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables.js"></script>
    <script>
        const dataTableBasic = new simpleDatatables.DataTable("#datatable-basic", {
            searchable: true,
            fixedHeight: false
        });
    </script>
    @endpush
</x-page-template>
