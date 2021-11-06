<x-app-layout>

    <section title="New Tournament">
        <div class="container-fluid mt-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted ">New Tournament</h6>

                    <form action="{{ route('tournament.store') }}" method="post" enctype="multipart/form-data">
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
                                <input type="text" name="tournamentName" class="form-control" required id="tournamentName"
                                    placeholder="Enter tournament name">
                            </div>
                            <div class="col-md-6 mt-1">
                                <label for="organizationName" class="form-label"> Organisation Name</label>
                                <input type="text" name="organizationName" class="form-control" required
                                    id="organizationName" placeholder="Enter organisation name">
                            </div>
                            <div class="col-md-6 mt-1">
                                <label for="organizationLogo" class="form-label">Organisation Logo</label>
                                <input class="form-control" accept="image/*" required type="file"
                                    id="organizationLogo" name="organizationLogo">
                            </div>
                            <div class="col-md-6 mt-1">
                                <label for="numberOfPlayers" class="form-label">Number of players</label>
                                <input type="number" name="numberOfPlayers" min="1" class="form-control" required
                                    id="numberOfPlayers" placeholder="Enter number of players">
                            </div>
                            <div class="col-md-6 mt-1">
                                <label for="primaryColor" class="form-label">Primary Color</label>
                                <input type="color" name="primaryColor" required class="form-control form-control-color"
                                    id="primaryColor" value="#8000ff" title="Choose your primary color">
                            </div>
                            <div class="col-md-6 mt-1">
                                <label for="secondaryColor" class="form-label">Secondary Color</label>
                                <input type="color" name="secondaryColor" required
                                    class="form-control form-control-color" id="secondaryColor" value="#0000ff"
                                    title="Choose your secondary color">
                            </div>
                            <div class="col-md-12 mt-1 text-end">
                                <button type="submit" class="btn btn-primary btn-sm ">Add </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
