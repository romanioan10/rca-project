<style>

body{
    background:#f3f5fb;
    font-family:Inter,sans-serif;
}

.dashboard-container{
    max-width:1200px;
    margin:0 auto;
    padding:50px 40px;
}

.dashboard-title{
    font-size:52px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:10px;
}

.dashboard-subtitle{
    font-size:20px;
    color:#64748b;
    margin-bottom:50px;
}

.dashboard-cards{
    display:flex;
    gap:30px;
    flex-wrap:wrap;
}

.dashboard-card{
    width:360px;
    background:#fff;
    border-radius:26px;
    padding:34px;
    text-decoration:none;
    color:#0f172a;
    border:1px solid #e5e7eb;
    box-shadow:
        0 10px 30px rgba(15,23,42,0.06);
    transition:all .25s ease;
    position:relative;
    overflow:hidden;
}

.dashboard-card:hover{
    transform:translateY(-8px);
    box-shadow:
        0 24px 50px rgba(15,23,42,0.12);
}

.card-icon{
    width:72px;
    height:72px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin-bottom:28px;
}

.card-icon.blue{
    background:#dbeafe;
    color:#2563eb;
}

.card-icon.purple{
    background:#ede9fe;
    color:#7c3aed;
}

.dashboard-card h3{
    font-size:30px;
    font-weight:800;
    margin-bottom:16px;
    color:#0f172a;
}

.dashboard-card p{
    font-size:17px;
    line-height:1.7;
    color:#475569;
    margin-bottom:34px;
}

.card-button{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:13px 22px;
    border-radius:14px;
    background:#2563eb;
    color:white;
    font-weight:700;
    font-size:15px;
}

.purple-btn{
    background:#7c3aed;
}

.topbar{
    background:white;
    border-bottom:1px solid #e5e7eb;
    padding:18px 34px;
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.logo-wrapper{
    display:flex;
    align-items:center;
    gap:14px;
}

.logo-box{
    width:52px;
    height:52px;
    border-radius:16px;
    background:#1d4ed8;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
}

.logo-text h2{
    margin:0;
    font-size:28px;
    font-weight:800;
    color:#0f172a;
}

.logo-text p{
    margin:0;
    color:#64748b;
    font-size:15px;
}

.logout-btn{
    padding:12px 18px;
    border-radius:14px;
    border:1px solid #dbe1ea;
    background:white;
    font-weight:600;
    cursor:pointer;
    transition:.2s;
}

.logout-btn:hover{
    background:#f8fafc;
}

@media(max-width:900px){

    .dashboard-cards{
        flex-direction:column;
    }

    .dashboard-card{
        width:100%;
    }

    .dashboard-title{
        font-size:38px;
    }

    .dashboard-container{
        padding:30px 20px;
    }

}
</style>

<div class="topbar">

    <div class="logo-wrapper">

        <div class="logo-box">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="28"
                 height="28"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="1.8"
                      d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z"/>

            </svg>

        </div>

        <div class="logo-text">
            <h2>BrokerRCA</h2>
            <p>Dashboard</p>
        </div>

    </div>

    <form method="POST" action="{{ route('rca.logout') }}">
        @csrf
        <button class="logout-btn">
            Logout
        </button>
    </form>

</div>

<div class="dashboard-container">

    <h1 class="dashboard-title">
        Dashboard RCA
    </h1>

    <p class="dashboard-subtitle">
        Alege rapid ce vrei să faci în aplicație.
    </p>

    <div class="dashboard-cards">

        <a href="{{ route('rca.offer.create') }}"
           class="dashboard-card">

            <div class="card-icon blue">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="34"
                     height="34"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M9 14l2-2 4 4m5-10H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V8a2 2 0 00-2-2zm-5-4h-6a2 2 0 00-2 2v2h10V4a2 2 0 00-2-2z"/>

                </svg>

            </div>

            <h3>
                Creează ofertă RCA
            </h3>

            <p>
                Completezi rapid datele vehiculului și
                primești automat toate ofertele disponibile.
            </p>

            <span class="card-button">
                Deschide formular
            </span>

        </a>

        <a href="{{ route('rca.chatbot') }}"
           class="dashboard-card">

            <div class="card-icon purple">

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="34"
                     height="34"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="1.8"
                          d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z"/>

                </svg>

            </div>

            <h3>
                Asistent RCA AI
            </h3>

            <p>
                Discută direct cu chatbotul și generează
                oferte RCA pas cu pas, într-un mod rapid și intuitiv.
            </p>

            <span class="card-button purple-btn">
                Deschide chatbot
            </span>

        </a>

    </div>

</div>