@extends('dashPatient.layout')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <h2>Paiement Paymee</h2>
                    </div>
                    <div class="card-body">
                        <p class="lead text-center">Cliquez sur le bouton ci-dessous pour effectuer votre paiement de manière
                            sécurisée.</p>
                        <p class="text-muted text-center">Vous serez redirigé vers Paymee pour compléter votre transaction.
                        </p>

                        <!-- Bouton de paiement -->
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ $paymeeUrl }}" class="btn btn-primary btn-lg" role="button"
                                aria-label="Payer maintenant">
                                <i class="fas fa-credit-card"></i> Payer maintenant
                            </a>
                        </div>

                        <!-- Optionnel: afficher un message de succès ou d'erreur si nécessaire -->
                        @if (session('success'))
                            <div class="alert alert-success mt-4">
                                {{ session('success') }}
                            </div>
                        @elseif (session('error'))
                            <div class="alert alert-danger mt-4">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
