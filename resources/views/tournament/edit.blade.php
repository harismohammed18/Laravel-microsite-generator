<x-app-layout>

    @isset($tournament)
        <section title="Update Tournament">
            <div class="container-fluid mt-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted ">Update Tournament</h6>

                        <form action="{{ route('tournament.update', ['id' => $tournament->id]) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                @if ($errors->any())
                                    <div class="col-md-12 mt-2 mb-3">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6 mt-1">
                                    <label for="tournamentName" class="form-label">Name</label>
                                    <input type="text" name="tournamentName" class="form-control" required
                                        id="tournamentName" placeholder="Enter tournament name"
                                        value="{{ $tournament->name }}">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label for="organizationName" class="form-label"> Organisation Name</label>
                                    <input type="text" name="organizationName" class="form-control" required
                                        id="organizationName" placeholder="Enter organisation name"
                                        value="{{ $tournament->org_name }}">
                                </div>
                                <div class="col-md-6 mt-1" id="organizationLogoSec">
                                    <label for="organizationLogo" class="form-label">Organisation Logo</label>
                                    <input class="form-control" accept="image/*" type="file" id="organizationLogo"
                                        name="organizationLogo">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label for="numberOfPlayers" class="form-label">Number of players</label>
                                    <input type="number" name="numberOfPlayers" min="1" class="form-control" required
                                        id="numberOfPlayers" placeholder="Enter number of players"
                                        value="{{ $tournament->no_of_players }}">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label for="primaryColor" class="form-label">Primary Color</label>
                                    <input type="color" name="primaryColor" required class="form-control form-control-color"
                                        id="primaryColor" value="{{ $tournament->primary_color }}"
                                        title="Choose your primary color">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <label for="secondaryColor" class="form-label">Secondary Color</label>
                                    <input type="color" name="secondaryColor" required
                                        class="form-control form-control-color" value="{{ $tournament->secondary_color }}"
                                        id="secondaryColor" value="#0000ff" title="Choose your secondary color">
                                </div>
                                <div class="col-md-6 mt-1" id="imagePreview">
                                    <div class="d-flex" style="align-items: self-end;">
                                        <label class="form-label">Organisation Logo</label>
                                        <button onclick="deleteImage()" data-bs-toggle="tooltip" title="Delete"
                                            data-bs-placement="top" class="btn btn-link"><i
                                                class="fa fa-trash"></i></button>
                                    </div>
                                    <img style="height: 200px" src="{{ asset('/uploads' . '/' . $tournament->org_logo) }}"
                                        title="orgnisation logo" />
                                </div>
                                <div class="col-md-12 mt-1 text-end">
                                    <button type="submit" class="btn btn-primary btn-sm ">Update </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    @endisset
    <script>
        var logoSection = document.getElementById("organizationLogoSec");
        if (logoSection) {
            logoSection.style.display = "none";
        }

        function deleteImage() {
            let previewSection = document.getElementById("imagePreview");
            let ogLogo = document.getElementById("organizationLogo");
            if (logoSection && previewSection) {
                logoSection.style.display = "block";
                ogLogo?.setAttribute("required", true)
                previewSection.style.display = "none";
            }
        }
    </script>
</x-app-layout>
