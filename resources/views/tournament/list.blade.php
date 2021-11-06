<x-app-layout>

    <section title="Available Tournaments">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12 p-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12 text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('tournament.create') }}"
                                        role="button"> New </a>
                                </div>
                                @if ($errors->any())
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-12 table-responsive mt-2">
                                    <table class="table table-hover ">
                                        <caption>Available Tournaments</caption>
                                        <thead>
                                            <tr>
                                                <th scope="row">#</th>
                                                <th scope="row">Name</th>
                                                <th scope="row">Organisation Name</th>
                                                <th scope="row">Number of Players</th>
                                                <th scope="row">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- {{dd($tournaments)}} --}}
                                            @if (isset($tournaments) && count($tournaments) > 0)
                                                @foreach ($tournaments as $tournament)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $tournament->name }}</td>
                                                        <td>{{ $tournament->org_name }}</td>
                                                        <td>{{ $tournament->no_of_players }}</td>
                                                        <td>

                                                            <a class="btn btn-link" data-bs-toggle="tooltip"
                                                                title="Download" data-bs-placement="top"
                                                                href="{{ route('tournament.download', ['id' => $tournament->id]) }}"
                                                                role="button"><i class="fa fa-download"
                                                                    aria-hidden="true"></i></a>
                                                            <button class="btn btn-link" data-bs-toggle="tooltip"
                                                                title="View" onclick="viewModal({{ $tournament }})"
                                                                data-bs-placement="top" role="button"><i
                                                                    class="fa fa-eye"
                                                                    aria-hidden="true"></i></button>
                                                            <a class="btn btn-link" data-bs-toggle="tooltip"
                                                                title="Edit" data-bs-placement="top"
                                                                href="{{ route('tournament.edit', ['id' => $tournament->id]) }}"
                                                                role="button"><i class="fa fa-pencil"
                                                                    aria-hidden="true"></i></a>
                                                            <a class="btn btn-link" data-bs-toggle="tooltip"
                                                                title="Delete"
                                                                href="{{ route('tournament.delete', ['id' => $tournament->id]) }}"
                                                                onclick="return confirm('Are you sure? you want to delete {{ $tournament->name }} ! ')"
                                                                data-bs-placement="top" role="button"><i
                                                                    class="fa fa-trash-o" aria-hidden="true"></i></a>

                                                        </td>

                                                    </tr>
                                                @endforeach

                                            @else
                                                <tr>
                                                    <td colspan="5">No Data</td>

                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-dialog-scrollable fade" id="tournamentDetail" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-labelledby="tournamentDetailLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tournamentDetailLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mt-1 row">
                                    <div class="col">
                                        <label for="tournamentName" class="form-label">Name</label>
                                    </div>
                                    <div class="col" id="tournamentName">: Name</div>
                                </div>
                                <div class="col-md-12 mt-1 row">
                                    <div class="col">
                                        <label for="orgName" class="form-label">Organisation Name</label>
                                    </div>
                                    <div class="col" id="orgName">: Organisation Name</div>
                                </div>
                                <div class="col-md-12 mt-1 row">
                                    <div class="col">
                                        <label for="noOfPlayers" class="form-label">Number of Players</label>
                                    </div>
                                    <div class="col" id="noOfPlayers">: Number of players</div>
                                </div>
                                <div class="col-md-12 mt-1 row">
                                    <div class="col">
                                        <label for="primaryColor" class="form-label">Primary Color</label>
                                    </div>
                                    <div class="col" id="primaryColor">: Black</div>
                                </div>
                                <div class="col-md-12 mt-1 row">
                                    <div class="col">
                                        <label for="secondaryColor" class="form-label">Secondary Color</label>
                                    </div>
                                    <div class="col" id="secondaryColor">: White</div>
                                </div>
                                <div class="col-md-12 mt-1 row">
                                    <div class="col">
                                        <label for="orgLogo" class="form-label">Organisation Logo</label>
                                    </div>
                                    <div class="col" id="orgLogo">: Name</div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function viewModal(tornament) {
            const url = "{{ asset('/uploads') }}";
            let modalElement = document.getElementById("tournamentDetail");
            if (modalElement) {
                let modalObj = new bootstrap.Modal(modalElement, {
                    backdrop: 'static'
                });

                const title = document.getElementById("tournamentDetailLabel");
                title.innerHTML = tornament?.name;

                const name = document.getElementById("tournamentName");
                name.innerHTML = `: ${tornament?.name}`;

                const orgName = document.getElementById("orgName");
                orgName.innerHTML = `: ${tornament?.org_name}`;

                const noOfPlayers = document.getElementById("noOfPlayers");
                noOfPlayers.innerHTML = `: ${tornament?.no_of_players}`;

                const span1 = document.createElement("div")
                span1.style.width = "20px";
                span1.style.height = "20px";

                const primaryColor = document.getElementById("primaryColor");
                span1.style.backgroundColor = `${tornament?.primary_color}`;
                primaryColor.replaceChildren(span1);

                const span2 = document.createElement("div")
                span2.style.width = "20px";
                span2.style.height = "20px";

                const secondaryColor = document.getElementById("secondaryColor");
                span2.style.backgroundColor = `${tornament?.secondary_color}`;
                secondaryColor.replaceChildren(span2);

                const orgLogo = document.getElementById("orgLogo");
                const image = document.createElement("img")
                image.style.width = "200px";
                image.setAttribute("src", url + "/" + tornament.org_logo)
                orgLogo.replaceChildren(image);
                modalObj.show();
            }
        }
    </script>
</x-app-layout>
