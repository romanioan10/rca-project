<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Chatbot RCA</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f5fb;
            color: #0f172a;
        }

        .chat-topbar {
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-logo {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .chat-logo-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .chat-logo-title {
            font-size: 24px;
            font-weight: 800;
        }

        .chat-logo-subtitle {
            font-size: 14px;
            color: #64748b;
        }

        .chat-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-nav a,
        .chat-nav button {
            padding: 11px 16px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: white;
            color: #0f172a;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .chat-nav a:hover,
        .chat-nav button:hover {
            background: #f8fafc;
        }

        .chat-page {
            min-height: calc(100vh - 85px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 34px;
        }

        .chat-container {
            width: 100%;
            max-width: 760px;
            height: 82vh;
            background: #ffffff;
            border-radius: 28px;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.16);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .chat-header {
            padding: 24px;
            background: linear-gradient(135deg, #0f172a, #111827);
            color: white;
        }

        .chat-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .chat-header p {
            margin: 7px 0 0;
            font-size: 14px;
            color: #d1d5db;
        }

        .reset-btn {
            margin-top: 12px;
            padding: 8px 13px;
            border: none;
            border-radius: 10px;
            background: #ef4444;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        .chat-messages {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            background: #f8fafc;
        }

        .message {
            max-width: 78%;
            padding: 13px 16px;
            border-radius: 16px;
            margin-bottom: 13px;
            font-size: 15px;
            line-height: 1.45;
        }

        .bot {
            background: #e5e7eb;
            color: #111827;
            border-bottom-left-radius: 5px;
        }

        .user {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }

        .chat-input-area {
            display: flex;
            gap: 10px;
            padding: 18px;
            border-top: 1px solid #e5e7eb;
            background: white;
        }

        .chat-input-area input {
            flex: 1;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            font-size: 15px;
        }

        .chat-input-area button {
            padding: 14px 22px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: white;
            font-weight: 700;
            cursor: pointer;
        }

        .summary-box,
        .offer-card {
            background: #fff;
            border: 1px solid #d1d5db;
            padding: 16px;
            border-radius: 14px;
            margin-top: 10px;
            font-size: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .offer-title {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 8px;
            text-transform: capitalize;
        }

        .offer-price {
            font-size: 16px;
            margin-bottom: 12px;
        }

        .choose-offer-btn,
        .policy-btn,
        .download-btn {
            display: inline-block;
            border: none;
            padding: 11px 17px;
            border-radius: 10px;
            color: white;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .choose-offer-btn {
            background: #2563eb;
        }

        .policy-btn,
        .download-btn {
            background: #16a34a;
        }

        @media (max-width: 800px) {
            .chat-topbar {
                flex-direction: column;
                gap: 14px;
                align-items: flex-start;
            }

            .chat-nav {
                flex-wrap: wrap;
            }

            .chat-page {
                padding: 16px;
            }

            .chat-container {
                height: 82vh;
            }

            .message {
                max-width: 92%;
            }
        }
    </style>
</head>
<body>

<div class="chat-topbar">
    <div class="chat-logo">
        <div class="chat-logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4z"/>
            </svg>
        </div>

        <div>
            <div class="chat-logo-title">BrokerRCA AI</div>
            <div class="chat-logo-subtitle">Asistent conversațional RCA</div>
        </div>
    </div>

    <div class="chat-nav">
        <a href="{{ route('rca.dashboard') }}">Dashboard</a>
        <a href="{{ route('rca.offer.create') }}">Formular clasic</a>

        <form method="POST" action="{{ route('rca.logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</div>

<div class="chat-page">
    <div class="chat-container">
        <div class="chat-header">
            <h1>Asistent RCA</h1>
            <p>Completează datele pas cu pas, iar la final îți vom genera ofertele RCA.</p>
            <button type="button" class="reset-btn" onclick="clearChatbotData()">Resetează datele</button>
        </div>

        <div class="chat-messages" id="chatMessages"></div>

        <div class="chat-input-area">
            <input type="text" id="chatInput" placeholder="Scrie răspunsul aici...">
            <button type="button" id="sendBtn">Trimite</button>
        </div>
    </div>
</div>

<script>
    const questions = [
        { key: 'startDate', question: 'Data de început RCA? Exemplu: 2026-05-20' },
        { key: 'termTime', question: 'Perioada RCA în luni? Exemplu: 6' },
        { key: 'installmentCount', question: 'Număr rate? Exemplu: 1' },
        { key: 'commissionPercentLimit', question: 'Comision procentual? Exemplu: 10' },

        { key: 'lastName', question: 'Nume de familie? Exemplu: Pop' },
        { key: 'firstName', question: 'Prenume? Exemplu: Vasile' },
        { key: 'personalCode', question: 'CNP proprietar? Exemplu: 1960701400013' },
        { key: 'birthdate', question: 'Data nașterii? Exemplu: 1996-07-01' },
        { key: 'gender', question: 'Sex? Exemplu: m' },
        { key: 'email', question: 'Email? Exemplu: ion.popescu@test.ro' },
        { key: 'phone', question: 'Telefon? Exemplu: 0722123456' },

        { key: 'idType', question: 'Tip act identitate? Exemplu: CI' },
        { key: 'idNumber', question: 'Serie și număr CI? Exemplu: CJ123456' },
        { key: 'idIssueAuthority', question: 'Autoritate emitere CI? Exemplu: SPCLEP Cluj' },
        { key: 'idIssueDate', question: 'Data emiterii CI? Exemplu: 2022-11-24' },
        { key: 'drivingLicenseIssueDate', question: 'Data emiterii permisului? Exemplu: 2016-06-15' },

        { key: 'country', question: 'Țara? Exemplu: RO' },
        { key: 'county', question: 'Județ? Exemplu: CJ' },
        { key: 'city', question: 'Localitate? Exemplu: Cluj-Napoca' },
        { key: 'cityCode', question: 'Cod localitate? Exemplu: 54984' },
        { key: 'street', question: 'Stradă? Exemplu: Principala' },
        { key: 'houseNumber', question: 'Număr stradă? Exemplu: 19-21' },
        { key: 'postcode', question: 'Cod poștal? Exemplu: 400356' },

        { key: 'licensePlate', question: 'Număr înmatriculare? Exemplu: CJ01ABC' },
        { key: 'registrationType', question: 'Tip înmatriculare? Exemplu: registered' },
        { key: 'vin', question: 'Serie șasiu / VIN? Exemplu: WAUZZZ8K1AA000025' },
        { key: 'vehicleType', question: 'Tip vehicul? Exemplu: M1' },
        { key: 'brand', question: 'Marcă auto? Exemplu: Audi' },
        { key: 'model', question: 'Model auto? Exemplu: A4' },
        { key: 'yearOfConstruction', question: 'An fabricație? Exemplu: 2018' },
        { key: 'engineDisplacement', question: 'Capacitate cilindrică? Exemplu: 1968' },
        { key: 'enginePower', question: 'Putere motor kW? Exemplu: 110' },
        { key: 'totalWeight', question: 'Masă maximă autorizată? Exemplu: 2050' },
        { key: 'seats', question: 'Număr locuri? Exemplu: 5' },
        { key: 'fuelType', question: 'Combustibil? Exemplu: diesel' },
        { key: 'firstRegistration', question: 'Prima înmatriculare? Exemplu: 2018-05-10' },
        { key: 'usageType', question: 'Utilizare? Exemplu: personal' },
        { key: 'vehicleIdentification', question: 'Număr CIV / identificare vehicul? Exemplu: G122737' },
        { key: 'currentMileage', question: 'Kilometraj actual? Exemplu: 120000' },
        { key: 'expirationDatePti', question: 'Data expirare ITP? Exemplu: 2026-06-16' }
    ];

    let currentQuestionIndex = 0;
    let offersCache = [];

    const savedFormData = localStorage.getItem('rcaChatbotFormData');
    const formData = savedFormData ? JSON.parse(savedFormData) : {};

    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');

    function addMessage(text, sender = 'bot') {
        const message = document.createElement('div');
        message.classList.add('message', sender);
        message.innerHTML = text;
        chatMessages.appendChild(message);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function askCurrentQuestion() {
        if (currentQuestionIndex < questions.length) {
            const currentQuestion = questions[currentQuestionIndex];

            if (formData[currentQuestion.key]) {
                addMessage(
                    currentQuestion.question +
                    '<br><small>Valoare salvată: <strong>' + formData[currentQuestion.key] + '</strong></small>' +
                    '<br>Apasă Enter ca să o păstrezi sau scrie altă valoare.',
                    'bot'
                );
            } else {
                addMessage(currentQuestion.question, 'bot');
            }
        } else {
            finishChat();
        }
    }

    function handleUserAnswer() {
        if (currentQuestionIndex >= questions.length) {
            return;
        }

        let answer = chatInput.value.trim();
        const currentQuestion = questions[currentQuestionIndex];

        if (!answer && formData[currentQuestion.key]) {
            answer = formData[currentQuestion.key];
        }

        if (!answer) {
            return;
        }

        addMessage(answer, 'user');

        formData[currentQuestion.key] = answer;
        localStorage.setItem('rcaChatbotFormData', JSON.stringify(formData));

        chatInput.value = '';
        currentQuestionIndex++;

        setTimeout(askCurrentQuestion, 400);
    }

    function clearChatbotData() {
        localStorage.removeItem('rcaChatbotFormData');
        window.location.reload();
    }

    function finishChat() {
        addMessage('Perfect. Am colectat datele necesare pentru cererea RCA.', 'bot');
        addMessage('Se generează ofertele RCA...', 'bot');

        chatInput.disabled = true;
        sendBtn.disabled = true;

        generateOffers();
    }

    async function generateOffers() {
        try {
            const response = await fetch('/rca/chatbot/create-offer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (!result.success) {
                addMessage(result.message || 'A apărut o eroare la generarea ofertelor.', 'bot');
                return;
            }

            if (!result.offers || result.offers.length === 0) {
                console.log(result.debug_response || result);
                addMessage('Nu au fost găsite oferte valide de la asiguratori.', 'bot');
                return;
            }

            offersCache = result.offers;

            addMessage('Am găsit ' + result.offers.length + ' ofertă/oferte valide.', 'bot');

            result.offers.forEach((offer, index) => {
                const insurer = offer.insurerName || offer.providerName || offer.providerKey || 'Asigurator';
                const price = offer.premiumAmount || offer.price || 'Necunoscut';
                const offerId = offer.offerId || '';

                addMessage(`
                    <div class="offer-card">
                        <div class="offer-title">${insurer}</div>
                        <div class="offer-price">Preț: <strong>${price} RON</strong></div>
                        ${offerId ? `<div style="font-size:12px;color:#6b7280;margin-bottom:10px;">Offer ID: ${offerId}</div>` : ''}
                        <button type="button" class="choose-offer-btn" onclick="selectOffer(${index})">
                            Alege oferta
                        </button>
                    </div>
                `, 'bot');
            });

        } catch (error) {
            console.error(error);
            addMessage('A apărut o eroare la generarea ofertelor.', 'bot');
        }
    }

    function selectOffer(index) {
        const offer = offersCache[index];

        if (!offer) {
            addMessage('Oferta selectată nu mai este disponibilă.', 'bot');
            return;
        }

        const insurer = offer.insurerName || offer.providerName || offer.providerKey || 'Asigurator';
        const price = offer.premiumAmount || offer.price || 'Necunoscut';
        const offerId = offer.offerId || '';

        addMessage('Am ales oferta ' + insurer + ' - ' + price + ' RON.', 'user');

        addMessage(`
            <div class="summary-box">
                <p><strong>Oferta selectată:</strong> ${insurer}</p>
                <p><strong>Preț:</strong> ${price} RON</p>
                ${offerId ? `<p><strong>Offer ID:</strong> ${offerId}</p>` : ''}

                <button type="button" class="policy-btn" onclick="generatePolicy('${offerId}')">
                    Generează poliță
                </button>
            </div>
        `, 'bot');
    }

    async function generatePolicy(offerId) {
        if (!offerId) {
            addMessage('Oferta selectată nu are Offer ID valid.', 'bot');
            return;
        }

        const selectedOffer = offersCache.find(
            offer => String(offer.offerId) === String(offerId)
        );

        addMessage('Se generează polița RCA...', 'bot');

        try {
            const response = await fetch('/rca/chatbot/policy', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    offerId: offerId,
                    amount: selectedOffer ? selectedOffer.premiumAmount : null,
                    email: formData.email
                })
            });

            const result = await response.json();

            console.log(result);

            if (!result.success) {
                let errorMessage = result.message || 'Eroare la generarea poliței.';

                if (result.errors && result.errors.payment) {
                    errorMessage += '<br>Detalii: ' + result.errors.payment.join(', ');
                }

                addMessage(errorMessage, 'bot');
                return;
            }

            addMessage('Polița a fost generată cu succes.', 'bot');

            const policyId =
                result.policyId ||
                result.data?.data?.policyId ||
                result.data?.policyId ||
                result.data?.data?.policies?.[0]?.policyId;

            if (policyId) {
                addMessage(`
                    <a href="/rca-policy/${policyId}/download" target="_blank" class="download-btn">
                        Descarcă polița PDF
                    </a>
                `, 'bot');
            } else {
                addMessage('Polița a fost generată, dar nu am primit un policyId pentru descărcare.', 'bot');
            }

            if (result.emailSent) {
                addMessage('Polița RCA a fost trimisă și pe email.', 'bot');
            }

        } catch (error) {
            console.error(error);
            addMessage('A apărut o eroare la generarea poliței.', 'bot');
        }
    }

    sendBtn.addEventListener('click', handleUserAnswer);

    chatInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            handleUserAnswer();
        }
    });

    window.onload = function () {
        addMessage('Bună! Te voi ajuta să obții o ofertă RCA.', 'bot');

        setTimeout(() => {
            askCurrentQuestion();
        }, 600);
    };
</script>

</body>
</html>