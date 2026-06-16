<!DOCTYPE html>
<html lang="{{ session('lang', 'fr') }}" dir="{{ session('lang', 'fr') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EtudServ – Trouver un Freelancer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap');

        :root {
            --blue:   #52D6F4;
            --blue2:  #39C6E9;
            --dark:   #1B2559;
            --purple: #7B61FF;
            --green:  #45C486;
            --bg:     #F4F7FB;
            --white:  #ffffff;
            --gray:   #8A94A6;
        }

        body {
            background-color: var(--bg);
            font-family: session('lang') === 'ar' ? 'Cairo' : 'Poppins', sans-serif;
            color: var(--dark);
        }

        .freelancer-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid #E9EDF7;
            box-shadow: 0 10px 25px rgba(27, 37, 89, 0.02);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .freelancer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(27, 37, 89, 0.06);
        }

        .avatar-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 20px;
        }

        .btn-contact {
            background-color: var(--purple);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            transition: opacity 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-contact:hover {
            opacity: 0.9;
            color: white;
        }
    </style>
</head>
<body>

<div class="container py-5">
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold" style="color: var(--dark);">
                {{ session('lang','fr') === 'ar' ? 'المستقلون المتاحون' : '🚀 Freelancers Disponibles' }}
            </h2>
            <p class="text-muted mb-0">
                {{ session('lang','fr') === 'ar' ? 'اكتشف أفضل المواهب لمشاريعك الدراسية' : 'Trouvez les meilleurs talents pour réaliser vos projets d\'études.' }}
            </p>
        </div>
        <a href="{{ route('client.dashboard') }}" class="btn btn-light px-4 border" style="border-radius: 8px; color: var(--dark); font-weight: 500;">
            <i class="fa-solid fa-arrow-left me-2"></i>{{ session('lang','fr') === 'ar' ? 'عودة' : 'Tableau de bord' }}
        </a>
    </div>

    <div class="row g-4">
        @forelse($freelancersData as $freelancer)
            <div class="col-md-4">
                <div class="card freelancer-card p-4">
                    
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-circle" style="background-color: {{ $freelancer['color'] }};">
                            {{ strtoupper(substr($freelancer['name'], 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0" style="color: var(--dark); font-size: 16px;">{{ $freelancer['name'] }}</h5>
                            <span class="text-muted small">{{ $freelancer['category'] }}</span>
                        </div>
                    </div>

                    <h6 class="fw-semibold mb-2" style="color: var(--purple);">{{ $freelancer['role'] }}</h6>
                    <p class="text-muted small mb-4" style="line-height: 1.5; height: 60px; overflow: hidden;">
                        {{ $freelancer['desc'] }}
                    </p>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #F4F7FB !important;">
                        <div>
                            <span class="text-muted small d-block" style="font-size: 11px;">{{ session('lang','fr') === 'ar' ? 'السعر يبدأ من' : 'À partir de' }}</span>
                            <span class="fw-bold fs-6" style="color: var(--dark);">{{ $freelancer['price'] }} MRU</span>
                        </div>
                        
                        <form action="{{ route('conversation.demarrer') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="service_id" value="{{ $freelancer['id_service'] }}">
                            <input type="hidden" name="utilisateur_id" value="{{ session('utilisateur_id') }}">
                            
                            <button type="submit" class="btn-contact">
                                <i class="fa-solid fa-paper-plane"></i> 
                                {{ session('lang','fr') === 'ar' ? 'تواصل' : 'Contacter' }}
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                {{ session('lang','fr') === 'ar' ? 'لا يوجد مستقلين متاحين حالياً.' : 'Aucun freelancer trouvé pour le moment.' }}
            </div>
        @endforelse
    </div>
</div>

</body>
</html>