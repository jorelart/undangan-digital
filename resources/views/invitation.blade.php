<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#273b34">
    <meta name="description" content="Undangan pernikahan {{ $invitation['couple'] }}. Merupakan kebahagiaan bagi kami jika Anda berkenan hadir.">
    <title>The Wedding of {{ $invitation['couple'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=DM+Sans:wght@400;500;600&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        :root {
            --forest: #273b34;
            --forest-deep: #1d2e28;
            --sage: #a6b29c;
            --cream: #f7f3e9;
            --paper: #fffdf7;
            --gold: #c9a86d;
            --ink: #27332d;
            --muted: #73786f;
            --serif: "Cormorant Garamond", "Iowan Old Style", Georgia, serif;
            --script: "Great Vibes", "Brush Script MT", cursive;
            --sans: "DM Sans", "Avenir Next", "Segoe UI", sans-serif;
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; scroll-padding-top: 28px; }
        body { margin: 0; color: var(--ink); background: var(--cream); font-family: var(--sans); }
        a { color: inherit; }
        button, input, textarea { font: inherit; }
        .opening {
            position: fixed; inset: 0; z-index: 10; display: grid; place-items: center; padding: 24px;
            color: var(--cream); text-align: center; background:
                linear-gradient(180deg, #1527204d, #172d27c9),
                radial-gradient(ellipse at 18% 15%, #758c7078 0, transparent 38%),
                radial-gradient(ellipse at 84% 82%, #c6a76a5c 0, transparent 36%),
                linear-gradient(145deg, #47614c, var(--forest-deep));
            transition: opacity 1s ease, visibility 1s ease;
            isolation: isolate;
        }
        .opening::before, .opening::after {
            position: absolute; content: ""; width: min(70vw, 440px); aspect-ratio: 1; border: 1px solid #d8c9a36b; border-radius: 50%;
        }
        .opening::before { animation: wreath-turn 48s linear infinite; }
        .opening::after { width: min(58vw, 360px); border-style: dashed; animation: wreath-turn 72s linear infinite reverse; }
        .opening-frame { position: absolute; inset: 15px; z-index: -1; border: 1px solid #e4d5ad59; pointer-events: none; }
        .opening-frame::before, .opening-frame::after { position: absolute; left: 50%; width: 1px; height: 55px; content: ""; background: linear-gradient(transparent, var(--gold)); }
        .opening-frame::before { top: 0; }
        .opening-frame::after { bottom: 0; transform: rotate(180deg); }
        .opening-sprig { position: absolute; z-index: 1; left: 50%; width: 158px; color: #d5bc83; transform: translateX(-50%); }
        .opening-sprig.opening-sprig { top: 26px; }
        .opening-footer-sprig { position: absolute; bottom: 18px; left: 50%; width: 112px; height: 32px; color: #d5bc83; transform: translateX(-50%); }
        .opening.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .opening-card { position: relative; z-index: 1; max-width: 560px; padding: 32px; animation: card-arrive 1.2s cubic-bezier(.2,.8,.2,1) both; }
        .eyebrow { margin: 0 0 14px; color: var(--gold); font-size: .68rem; letter-spacing: .28em; text-transform: uppercase; }
        .opening h1 { margin: 0; color: #e4cf99; font: 400 clamp(4.1rem, 12vw, 7.7rem)/1.05 var(--script); text-shadow: 0 5px 28px #17251f50; }
        .opening-date { margin: 12px 0 5px; font-family: var(--serif); font-size: 1.35rem; letter-spacing: .24em; }
        .small-label { color: #e4dfd1; font-size: .78rem; letter-spacing: .11em; }
        .guest-label { margin-top: 36px; color: #ded9cd; font-size: .83rem; }
        .guest-name { margin: 7px 0 24px; font: 1.5rem var(--serif); }
        .button {
            display: inline-flex; min-height: 48px; align-items: center; justify-content: center; gap: 10px;
            border: 1px solid var(--gold); border-radius: 2px; padding: 0 24px; color: var(--cream);
            background: transparent; text-decoration: none; letter-spacing: .08em; cursor: pointer; transition: color .3s ease, background .3s ease, transform .3s ease;
        }
        .button:hover { color: var(--forest); background: var(--gold); transform: translateY(-2px); }
        .button-solid { color: var(--paper); background: var(--forest); }
        .button-solid:hover { color: var(--forest); background: var(--gold); }
        .main { width: min(100% - 36px, 1120px); margin: 0 auto; }
        .hero {
            min-height: 720px; display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 36px; padding: 76px 0;
        }
        .hero-copy { padding: 32px 0; }
        .hero h2 { max-width: 600px; margin: 0; font: 500 clamp(3.5rem, 7vw, 6.2rem)/.82 var(--serif); letter-spacing: -.045em; }
        .hero h2 span { display: block; margin: .09em 0 0 .04em; color: #71816f; font: 400 clamp(4.1rem, 8vw, 7.2rem)/1.05 var(--script); letter-spacing: 0; white-space: nowrap; }
        .hero-quote { max-width: 460px; margin: 26px 0 32px; color: var(--muted); line-height: 1.8; }
        .hero-art {
            position: relative; min-height: 550px; display: grid; place-items: center; overflow: hidden;
            border-radius: 230px 230px 4px 4px; color: var(--cream);
            background: radial-gradient(ellipse at 50% 37%, #98a17c 0, transparent 25%),
                radial-gradient(ellipse at 52% 85%, #b99b6c 0, transparent 28%),
                linear-gradient(180deg, #aebca4 0%, #697d65 42%, #293f34 100%);
            box-shadow: 0 30px 70px #28372b20;
        }
        .hero-art::before { position: absolute; inset: 20px; z-index: 1; content: ""; border: 1px solid #ded1aa80; border-radius: 220px 220px 2px 2px; pointer-events: none; }
        .hero-art::after { position: absolute; inset: 17% 18%; content: ""; border: 1px solid #d8c9a34a; border-radius: 50%; transform: rotate(35deg); animation: wreath-breathe 7s ease-in-out infinite; }
        .hero-botanical { position: absolute; inset: 0; width: 100%; height: 100%; opacity: .72; }
        .art-caption { position: relative; z-index: 2; margin-top: 165px; text-align: center; text-shadow: 0 3px 22px #192b23a6; }
        .art-caption small { display: block; margin-bottom: 2px; color: #e4d5ad; font-size: .63rem; letter-spacing: .3em; text-transform: uppercase; }
        .art-caption strong { display: block; color: #fff9eb; font: 400 clamp(3.2rem, 6vw, 5.3rem)/1.14 var(--script); }
        .section { position: relative; padding: 100px 0; text-align: center; }
        .section-dark { padding-right: 18px; padding-left: 18px; color: var(--cream); background: var(--forest); }
        .section h2 { margin: 0 0 14px; font: 400 clamp(2.8rem, 5vw, 4.8rem)/.95 var(--serif); }
        .section-intro { max-width: 610px; margin: 0 auto 42px; color: var(--muted); line-height: 1.8; }
        .section-dark .section-intro { color: #d3d5cb; }
        .ornament { display: flex; width: 128px; align-items: center; gap: 11px; margin: 23px auto; color: var(--gold); }
        .ornament::before, .ornament::after { height: 1px; flex: 1; content: ""; background: currentColor; }
        .ornament svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 1.2; }
        .floral-divider { display: block; width: 130px; height: 40px; margin: 22px auto 27px; overflow: visible; color: var(--gold); }
        .section-dark .floral-divider { color: #dfc891; }
        .couple-grid { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 35px; max-width: 850px; margin: 0 auto; }
        .person { padding: 22px; }
        .person-monogram { position: relative; width: 162px; height: 204px; display: grid; place-items: center; margin: 0 auto 26px; border: 1px solid #c9a86d80; border-radius: 100px 100px 3px 3px; color: var(--forest); background: radial-gradient(ellipse at 48% 35%, #edf0df 0, #d7ddcc 35%, #aab79f 100%); font: 4.6rem var(--script); box-shadow: 0 15px 38px #28372b16; }
        .person-monogram::after { position: absolute; inset: 9px; content: ""; border: 1px solid #c9a86d72; border-radius: 100px 100px 0 0; }
        .person h3 { margin: 0; color: var(--forest); font: 400 2.8rem var(--script); }
        .person p { margin: 10px 0 0; color: var(--muted); line-height: 1.7; }
        .ampersand { color: var(--gold); font: 400 4rem var(--script); }
        .countdown { display: flex; justify-content: center; gap: clamp(14px, 5vw, 58px); margin: 42px 0 10px; }
        .countdown div { min-width: 70px; }
        .countdown strong { display: block; font: 400 clamp(2rem, 5vw, 3.8rem) var(--serif); }
        .countdown span { color: #d1d4ca; font-size: .76rem; letter-spacing: .12em; text-transform: uppercase; }
        .event-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; max-width: 820px; margin: 0 auto; text-align: left; }
        .event-card { position: relative; padding: 38px 30px 30px; border: 1px solid #d6d0c1; background: var(--paper); transition: transform .45s ease, box-shadow .45s ease; }
        .event-card::before { position: absolute; inset: 8px; content: ""; border: 1px solid #d6d0c180; pointer-events: none; }
        .event-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px #273b341a; }
        .event-card h3 { margin: 0 0 18px; color: var(--forest); font: 400 2.5rem var(--serif); }
        .event-card p { margin: 8px 0; color: var(--muted); line-height: 1.6; }
        .event-card .event-date { color: var(--ink); font-weight: 600; }
        .map-link { display: inline-block; margin-top: 16px; color: #657660; font-size: .85rem; text-underline-offset: 4px; }
        .story-list { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 960px; margin: 44px auto 0; text-align: left; }
        .story-item { position: relative; padding: 25px 22px; border-top: 1px solid var(--gold); }
        .story-item::before { position: absolute; top: -4px; left: 22px; width: 7px; height: 7px; content: ""; border: 1px solid var(--gold); border-radius: 50%; background: var(--cream); }
        .story-item small { color: var(--gold); letter-spacing: .14em; }
        .story-item h3 { margin: 10px 0; font: 400 1.7rem var(--serif); }
        .story-item p { margin: 0; color: var(--muted); line-height: 1.75; }
        .gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; max-width: 840px; margin: 34px auto 0; }
        .gallery-tile { position: relative; min-height: 200px; display: grid; place-items: center; overflow: hidden; border: 1px solid #d1c7ae; color: #fff9eb; font: 400 2rem var(--script); text-shadow: 0 2px 12px #273b34b3; background: linear-gradient(140deg, #738675, #c6b798); transition: transform .6s ease, box-shadow .6s ease; }
        .gallery-tile::before, .gallery-tile::after { position: absolute; content: ""; pointer-events: none; }
        .gallery-tile::before { inset: 9px; border: 1px solid #fff9eb80; }
        .gallery-tile::after { inset: 22% 28%; border: 1px solid #fff9eb4f; border-radius: 50%; transform: rotate(-30deg); }
        .gallery-tile:hover { z-index: 1; transform: scale(1.035); box-shadow: 0 15px 36px #273b3433; }
        .gallery-tile:nth-child(2), .gallery-tile:nth-child(5) { background: linear-gradient(155deg, #b2bda8, #e2d6bf); }
        .rsvp-layout { display: grid; grid-template-columns: .8fr 1.2fr; gap: 54px; align-items: start; max-width: 900px; margin: 0 auto; text-align: left; }
        .rsvp-note { padding: 30px; color: var(--cream); background: var(--forest); }
        .rsvp-note h3 { margin: 0 0 18px; font: 400 2rem var(--serif); }
        .rsvp-note p { color: #d7d9d0; line-height: 1.8; }
        .stat-row { display: flex; gap: 24px; margin-top: 25px; }
        .stat-row strong { display: block; color: #e0c58f; font: 400 1.8rem var(--serif); }
        .stat-row span { color: #d7d9d0; font-size: .8rem; }
        .form-field { margin-bottom: 18px; }
        .form-field label, .form-label { display: block; margin-bottom: 8px; font-size: .88rem; font-weight: 600; }
        .form-field input, .form-field textarea {
            width: 100%; border: 1px solid #d3ccbd; border-radius: 2px; padding: 13px 14px; color: var(--ink); background: var(--paper);
        }
        .form-field textarea { min-height: 115px; resize: vertical; }
        .form-field input:focus, .form-field textarea:focus { outline: 2px solid #a6b29c; outline-offset: 1px; }
        .attendance-options { display: flex; gap: 10px; }
        .attendance-option { position: relative; flex: 1; }
        .attendance-option input { position: absolute; width: 1px; height: 1px; margin: 0; border: 0; padding: 0; opacity: 0; }
        .attendance-option span { display: block; border: 1px solid #d3ccbd; padding: 12px; text-align: center; cursor: pointer; }
        .attendance-option input:checked + span { border-color: var(--forest); color: var(--paper); background: var(--forest); }
        .attendance-option input:focus-visible + span { outline: 2px solid var(--gold); outline-offset: 2px; }
        .status-message { margin: 0 auto 25px; padding: 14px 18px; max-width: 900px; color: #1f573f; background: #e4efe5; }
        .error-list { margin: 0 auto 25px; padding: 14px 32px; max-width: 900px; color: #873e31; background: #f7e7e2; text-align: left; }
        .wishes { max-width: 760px; margin: 52px auto 0; text-align: left; }
        .wishes h3 { text-align: center; font: 400 2rem var(--serif); }
        .wish { padding: 18px 0; border-bottom: 1px solid #dcd5c7; }
        .wish strong { font: 400 1.25rem var(--serif); }
        .wish p { margin: 7px 0 0; color: var(--muted); line-height: 1.65; }
        .footer { position: relative; margin-top: 0; overflow: hidden; padding: 65px 20px; color: var(--cream); background: radial-gradient(ellipse at 50% 130%, #52654e, transparent 60%), var(--forest-deep); text-align: center; }
        .footer p { color: #d1d4ca; }
        .footer strong { display: block; color: #e4cf99; font: 400 4.4rem var(--script); }
        .js-enabled .reveal { opacity: 0; transform: translateY(26px); transition: opacity .8s ease, transform .8s cubic-bezier(.2,.8,.2,1); }
        .js-enabled .reveal.is-visible { opacity: 1; transform: translateY(0); }
        .js-enabled .reveal-delay-1 { transition-delay: .12s; }
        .js-enabled .reveal-delay-2 { transition-delay: .24s; }
        .js-enabled .reveal-delay-3 { transition-delay: .36s; }
        .js-enabled .opening.is-hidden ~ main .hero-copy,
        .js-enabled .opening.is-hidden ~ main .hero-art { transition-duration: 1.1s; }
        @keyframes wreath-turn { to { transform: rotate(360deg); } }
        @keyframes wreath-breathe { 0%, 100% { transform: rotate(35deg) scale(.94); opacity: .55; } 50% { transform: rotate(35deg) scale(1.04); opacity: 1; } }
        @keyframes card-arrive { from { opacity: 0; transform: translateY(18px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        @media (max-width: 720px) {
            .hero { min-height: auto; grid-template-columns: 1fr; gap: 12px; padding: 50px 0 70px; }
            .hero-copy { padding: 15px 0; }
            .hero-art { min-height: 390px; grid-row: 1; }
            .hero h2 { font-size: clamp(3.1rem, 14vw, 5.4rem); }
            .hero h2 span { font-size: clamp(3.8rem, 15vw, 5.8rem); }
            .hero-quote { max-width: 100%; }
            .section { padding: 68px 0; }
            .couple-grid { grid-template-columns: 1fr; gap: 6px; }
            .person { padding: 10px; }
            .person-monogram { width: 132px; height: 166px; }
            .ampersand { line-height: 1; }
            .event-grid, .rsvp-layout { grid-template-columns: 1fr; gap: 18px; }
            .story-list { grid-template-columns: 1fr; gap: 5px; }
            .gallery-grid { grid-template-columns: 1fr 1fr; }
            .gallery-tile { min-height: 145px; }
            .opening-frame { inset: 9px; }
            .opening h1 { font-size: clamp(3.8rem, 17vw, 5.3rem); }
            .section { padding: 76px 0; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
            .js-enabled .reveal { opacity: 1; transform: none; }
        }
    </style>
</head>
<body>
    <div class="opening" id="opening">
        <div class="opening-frame" aria-hidden="true"></div>
        <svg class="floral-divider opening-sprig" viewBox="0 0 180 48" aria-hidden="true">
            <path d="M90 43V17M90 28c-10-1-18-7-21-16 11 1 19 7 21 16Zm0 5c10-1 18-7 21-16-11 1-19 7-21 16ZM90 23c-8-7-8-15 0-21 8 6 8 14 0 21Zm-9 19H41m98 0H99M51 42c7-8 13-8 19 0-6 8-12 8-19 0Zm78 0c-7-8-13-8-19 0 6 8 12 8 19 0Z" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <div class="opening-card">
            <p class="eyebrow">The Wedding of</p>
            <h1>{{ $invitation['couple'] }}</h1>
            <p class="opening-date">{{ \Illuminate\Support\Carbon::parse($invitation['date'])->format('d . m . Y') }}</p>
            <p class="small-label">Dengan penuh rasa syukur, kami mengundang Anda</p>
            <p class="guest-label">Kepada Yth.</p>
            <p class="guest-name">{{ $guestName }}</p>
            <button class="button" id="openInvitation" type="button">Buka Undangan <span aria-hidden="true">↓</span></button>
        </div>
        <svg class="floral-divider opening-footer-sprig" viewBox="0 0 180 48" aria-hidden="true">
            <path d="M90 5v26m0-11c-10 1-18 7-21 16 11-1 19-7 21-16Zm0-5c10 1 18 7 21 16-11-1-19-7-21-16Zm0 16c-8 7-8 15 0 21 8-6 8-14 0-21ZM41 6h49m49 0H90m-30 0c7 8 13 8 19 0-6-8-12-8-19 0Zm78 0c-7 8-13 8-19 0 6-8 12-8 19 0Z" fill="none" stroke="currentColor" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    <main>
        <div class="main">
            <section class="hero" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow">The Wedding of</p>
                    <h2 id="hero-title">{{ $invitation['groom'] }} <span>&amp; {{ $invitation['bride'] }}</span></h2>
                    <div class="ornament" style="margin-left:0" aria-hidden="true">
                        <svg viewBox="0 0 20 20"><path d="M10 1.5c1.8 3.3 5.2 6.7 8.5 8.5-3.3 1.8-6.7 5.2-8.5 8.5C8.2 15.2 4.8 11.8 1.5 10 4.8 8.2 8.2 4.8 10 1.5Z"/><circle cx="10" cy="10" r="2.3"/></svg>
                    </div>
                    <p class="hero-quote">“Dan di antara tanda-tanda kebesaran-Nya ialah Dia menciptakan pasangan-pasangan untukmu agar kamu merasa tenteram dan dijadikan-Nya di antaramu rasa kasih dan sayang.” <span class="small-label">(QS. Ar-Rum: 21)</span></p>
                    <a class="button button-solid" href="#acara">Lihat Detail Acara <span aria-hidden="true">↓</span></a>
                </div>
                <div class="hero-art" role="img" aria-label="Ilustrasi abstrak bernuansa alam untuk pernikahan">
                    <svg class="hero-botanical" viewBox="0 0 500 620" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                        <defs>
                            <linearGradient id="arch-fill" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#d9dfc3" stop-opacity=".26"/><stop offset="1" stop-color="#24392e" stop-opacity=".06"/></linearGradient>
                            <linearGradient id="hill-fill" x1="0" y1="0" x2="0" y2="1"><stop stop-color="#445e49"/><stop offset="1" stop-color="#1d342b"/></linearGradient>
                        </defs>
                        <circle cx="251" cy="177" r="100" fill="#ead6a0" opacity=".14"/>
                        <path d="M96 366V205a154 154 0 0 1 308 0v161" fill="url(#arch-fill)" stroke="#efe4c2" stroke-opacity=".39" stroke-width="1.4"/>
                        <path d="M125 366V210a125 125 0 0 1 250 0v156M145 366V215a105 105 0 0 1 210 0v151" fill="none" stroke="#efe4c2" stroke-opacity=".2"/>
                        <path d="M126 368h248M143 383h214M160 397h180" fill="none" stroke="#ead9ad" stroke-opacity=".56" stroke-width="1.4"/>
                        <path d="M0 473c63-46 112-38 174 1 69 44 124 28 185-1 53-26 92-25 141-1v148H0Z" fill="url(#hill-fill)" opacity=".8"/>
                        <path d="M0 508c66-28 108-15 155 14 60 37 105 28 162 0 68-34 120-26 183 5" fill="none" stroke="#b9b48a" stroke-opacity=".37"/>
                        <g fill="none" stroke="#dfd9b7" stroke-opacity=".78" stroke-width="1.6" stroke-linecap="round">
                            <path d="M20 535C60 481 52 424 34 376m14 106c20-28 40-36 65-39m-53 6c-5-29-22-49-39-58m70 82c7-40 30-62 54-81"/>
                            <path d="M478 549c-43-64-34-122-15-166m-12 109c-20-31-42-41-65-45m53 10c7-30 24-48 41-58m-72 84c-6-42-28-66-52-86"/>
                            <path d="M28 445c-13-2-21-9-27-21 14 0 23 7 27 21Zm15 32c-14-1-23-7-31-18 14-2 24 4 31 18Zm19-36c-3-15 1-26 11-37 5 14 2 26-11 37Zm307 17c13-2 22-9 28-21-14 0-23 7-28 21Zm-16 32c14-1 23-7 31-18-14-2-24 4-31 18Zm-19-36c3-15-1-26-11-37-5 14-2 26 11 37Z"/>
                        </g>
                        <g fill="#e6cb96" fill-opacity=".88">
                            <path d="M82 531c-15-8-19-18-13-31 13 5 19 15 13 31Zm-8-5c5-16 15-22 29-18-4 14-14 21-29 18Zm7-14c-3-17 3-26 17-30 3 15-3 25-17 30Zm-4 1c-15 6-27 2-33-11 13-8 25-4 33 11Z"/>
                            <path d="M422 526c15-8 19-18 13-31-13 5-19 15-13 31Zm8-5c-5-16-15-22-29-18 4 14 14 21 29 18Zm-7-14c3-17-3-26-17-30-3 15 3 25 17 30Zm4 1c15 6 27 2 33-11-13-8-25-4-33 11Z"/>
                        </g>
                        <g fill="#f2e6c7" opacity=".76">
                            <circle cx="77" cy="512" r="3"/><circle cx="414" cy="507" r="3"/><circle cx="57" cy="550" r="2.2"/><circle cx="444" cy="548" r="2.2"/>
                        </g>
                    </svg>
                    <div class="art-caption"><small>Save the date</small><strong>{{ $invitation['couple'] }}</strong></div>
                </div>
            </section>

            <section class="section" id="mempelai">
                <p class="eyebrow">Dengan memohon rahmat dan ridho Allah SWT</p>
                <h2>Assalamu’alaikum Warahmatullahi Wabarakatuh</h2>
                <svg class="floral-divider" viewBox="0 0 180 48" aria-hidden="true">
                    <path d="M90 42V17m0 14c-11-1-19-7-22-17 11 1 19 7 22 17Zm0-6c11-1 19-7 22-17-11 1-19 7-22 17Zm0-5c-9-7-9-15 0-21 9 6 9 14 0 21ZM4 25h58m54 0h60m-150 0c8-12 17-12 25 0-8 12-17 12-25 0Zm130 0c-8-12-17-12-25 0 8 12 17 12 25 0Z" fill="none" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="section-intro">Dengan penuh kebahagiaan, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dan memberikan doa restu pada pernikahan kami.</p>
                <div class="couple-grid">
                    <article class="person">
                        <div class="person-monogram" aria-hidden="true">{{ mb_substr($invitation['groom'], 0, 1) }}</div>
                        <h3>{{ $invitation['groom'] }}</h3>
                        <p>{{ $invitation['groom_parents'] }}</p>
                    </article>
                    <span class="ampersand" aria-hidden="true">&amp;</span>
                    <article class="person">
                        <div class="person-monogram" aria-hidden="true">{{ mb_substr($invitation['bride'], 0, 1) }}</div>
                        <h3>{{ $invitation['bride'] }}</h3>
                        <p>{{ $invitation['bride_parents'] }}</p>
                    </article>
                </div>
            </section>
        </div>

        <section class="section section-dark" id="date">
            <p class="eyebrow">Sebuah hari untuk dikenang</p>
            <h2>Menuju Hari Bahagia</h2>
            <svg class="floral-divider" viewBox="0 0 180 48" aria-hidden="true">
                <path d="M90 42V17m0 14c-11-1-19-7-22-17 11 1 19 7 22 17Zm0-6c11-1 19-7 22-17-11 1-19 7-22 17Zm0-5c-9-7-9-15 0-21 9 6 9 14 0 21ZM4 25h58m54 0h60m-150 0c8-12 17-12 25 0-8 12-17 12-25 0Zm130 0c-8-12-17-12-25 0 8 12 17 12 25 0Z" fill="none" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="section-intro">{{ $invitation['date_label'] }}. Kehadiran dan doa Anda akan menjadi kebahagiaan bagi kami.</p>
            <div class="countdown" id="countdown" data-date="{{ $invitation['date'] }}" aria-label="Hitung mundur hari pernikahan">
                <div><strong data-unit="days">--</strong><span>Hari</span></div>
                <div><strong data-unit="hours">--</strong><span>Jam</span></div>
                <div><strong data-unit="minutes">--</strong><span>Menit</span></div>
                <div><strong data-unit="seconds">--</strong><span>Detik</span></div>
            </div>
        </section>

        <div class="main">
            <section class="section" id="acara">
                <p class="eyebrow">Dengan bahagia kami menanti kehadiran Anda</p>
                <h2>Detail Acara</h2>
                <svg class="floral-divider" viewBox="0 0 180 48" aria-hidden="true">
                    <path d="M90 42V17m0 14c-11-1-19-7-22-17 11 1 19 7 22 17Zm0-6c11-1 19-7 22-17-11 1-19 7-22 17Zm0-5c-9-7-9-15 0-21 9 6 9 14 0 21ZM4 25h58m54 0h60m-150 0c8-12 17-12 25 0-8 12-17 12-25 0Zm130 0c-8-12-17-12-25 0 8 12 17 12 25 0Z" fill="none" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="event-grid">
                    <article class="event-card">
                        <p class="eyebrow">08.00 WIB</p>
                        <h3>Akad Nikah</h3>
                        <p class="event-date">{{ $invitation['date_label'] }}</p>
                        <p>{{ $invitation['ceremony_time'] }}</p>
                        <p><strong>{{ $invitation['venue'] }}</strong><br>{{ $invitation['address'] }}</p>
                        <a class="map-link" href="{{ $invitation['map_url'] }}" target="_blank" rel="noopener noreferrer">Petunjuk lokasi ↗</a>
                    </article>
                    <article class="event-card">
                        <p class="eyebrow">Merayakan bersama</p>
                        <h3>Resepsi</h3>
                        <p class="event-date">{{ $invitation['date_label'] }}</p>
                        <p>{{ $invitation['reception_time'] }}</p>
                        <p><strong>{{ $invitation['venue'] }}</strong><br>{{ $invitation['address'] }}</p>
                        <a class="map-link" href="{{ $invitation['map_url'] }}" target="_blank" rel="noopener noreferrer">Petunjuk lokasi ↗</a>
                    </article>
                </div>
            </section>

            <section class="section" id="cerita">
                <p class="eyebrow">Perjalanan kami</p>
                <h2>Sepenggal Kisah</h2>
                <svg class="floral-divider" viewBox="0 0 180 48" aria-hidden="true">
                    <path d="M90 42V17m0 14c-11-1-19-7-22-17 11 1 19 7 22 17Zm0-6c11-1 19-7 22-17-11 1-19 7-22 17Zm0-5c-9-7-9-15 0-21 9 6 9 14 0 21ZM4 25h58m54 0h60m-150 0c8-12 17-12 25 0-8 12-17 12-25 0Zm130 0c-8-12-17-12-25 0 8 12 17 12 25 0Z" fill="none" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="section-intro">Setiap pertemuan menyimpan cerita. Inilah beberapa momen yang membawa kami menuju hari istimewa.</p>
                <div class="story-list">
                    @foreach ($invitation['story'] as $index => $story)
                        <article class="story-item">
                            <small>0{{ $index + 1 }}</small>
                            <h3>{{ $story['title'] }}</h3>
                            <p>{{ $story['body'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="section" id="galeri">
                <p class="eyebrow">Momen penuh makna</p>
                <h2>Galeri</h2>
                <svg class="floral-divider" viewBox="0 0 180 48" aria-hidden="true">
                    <path d="M90 42V17m0 14c-11-1-19-7-22-17 11 1 19 7 22 17Zm0-6c11-1 19-7 22-17-11 1-19 7-22 17Zm0-5c-9-7-9-15 0-21 9 6 9 14 0 21ZM4 25h58m54 0h60m-150 0c8-12 17-12 25 0-8 12-17 12-25 0Zm130 0c-8-12-17-12-25 0 8 12 17 12 25 0Z" fill="none" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="section-intro">Ruang galeri ini siap diisi foto pilihan Anda saat personalisasi undangan.</p>
                <div class="gallery-grid" aria-label="Placeholder galeri foto">
                    <div class="gallery-tile">Satu cerita</div><div class="gallery-tile">Dua hati</div><div class="gallery-tile">Satu tujuan</div>
                    <div class="gallery-tile">Hari istimewa</div><div class="gallery-tile">Penuh syukur</div><div class="gallery-tile">Selamanya</div>
                </div>
            </section>
        </div>

        <section class="section section-dark" id="rsvp">
            <p class="eyebrow">Kehadiran Anda berarti bagi kami</p>
            <h2>Konfirmasi Kehadiran</h2>
            <svg class="floral-divider" viewBox="0 0 180 48" aria-hidden="true">
                <path d="M90 42V17m0 14c-11-1-19-7-22-17 11 1 19 7 22 17Zm0-6c11-1 19-7 22-17-11 1-19 7-22 17Zm0-5c-9-7-9-15 0-21 9 6 9 14 0 21ZM4 25h58m54 0h60m-150 0c8-12 17-12 25 0-8 12-17 12-25 0Zm130 0c-8-12-17-12-25 0 8 12 17 12 25 0Z" fill="none" stroke="currentColor" stroke-width="1.15" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="section-intro">Mohon luangkan waktu untuk mengisi konfirmasi dan menyampaikan doa terbaik.</p>
            @if (session('status'))
                <p class="status-message" role="status">{{ session('status') }}</p>
            @endif
            @if ($errors->any())
                <ul class="error-list" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="rsvp-layout">
                <aside class="rsvp-note">
                    <p class="eyebrow">Terima kasih</p>
                    <h3>Sampai jumpa di hari bahagia kami.</h3>
                    <p>Konfirmasi kehadiran Anda membantu kami mempersiapkan hari istimewa dengan lebih baik. Setiap doa dan ucapan baik akan menjadi kenangan indah yang kami simpan.</p>
                </aside>
                <form action="{{ route('rsvps.store') }}" method="post">
                    @csrf
                    <div class="form-field">
                        <label for="guest_name">Nama</label>
                        <input id="guest_name" name="guest_name" type="text" value="{{ old('guest_name', $guestName === 'Tamu Undangan' ? '' : $guestName) }}" maxlength="120" autocomplete="name" required>
                    </div>
                    <fieldset class="form-field" style="border:0;padding:0">
                        <legend class="form-label">Apakah Anda akan hadir?</legend>
                        <div class="attendance-options">
                            <label class="attendance-option"><input type="radio" name="attendance" value="attending" @checked(old('attendance') === 'attending') required><span>Insyaallah hadir</span></label>
                            <label class="attendance-option"><input type="radio" name="attendance" value="not_attending" @checked(old('attendance') === 'not_attending')><span>Belum dapat hadir</span></label>
                        </div>
                    </fieldset>
                    <div class="form-field" id="guestCountField" hidden>
                        <label for="guest_count">Jumlah tamu (termasuk Anda)</label>
                        <input id="guest_count" name="guest_count" type="number" min="1" max="10" value="{{ old('guest_count', 1) }}">
                    </div>
                    <div class="form-field">
                        <label for="message">Ucapan dan doa <span class="small-label">(opsional)</span></label>
                        <textarea id="message" name="message" maxlength="500" placeholder="Tuliskan doa terbaik Anda...">{{ old('message') }}</textarea>
                    </div>
                    <button class="button button-solid" type="submit">Kirim Konfirmasi <span aria-hidden="true">→</span></button>
                </form>
            </div>
            <div class="wishes">
                <h3>Doa dan Ucapan</h3>
                @forelse ($wishes as $wish)
                    <article class="wish">
                        <strong>{{ $wish->guest_name }}</strong>
                        <p>{{ $wish->message }}</p>
                    </article>
                @empty
                    <p class="section-intro">Jadilah yang pertama menyampaikan ucapan dan doa.</p>
                @endforelse
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir.</p>
        <p>Kami yang berbahagia,</p>
        <strong>{{ $invitation['couple'] }}</strong>
    </footer>
    <script>
        document.documentElement.classList.add('js-enabled');

        const revealItems = document.querySelectorAll(
            '.hero-copy, .hero-art, .section > .eyebrow, .section > h2, .section > .floral-divider, .section > .section-intro, .person, .countdown > div, .event-card, .story-item, .gallery-tile, .rsvp-note, .rsvp-layout form, .wishes, .footer p, .footer strong'
        );

        if ('IntersectionObserver' in window) {
            const revealObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });

            revealItems.forEach((item, index) => {
                item.classList.add('reveal', `reveal-delay-${index % 4}`);
                if (!item.matches('.hero-copy, .hero-art')) {
                    revealObserver.observe(item);
                }
            });
        } else {
            revealItems.forEach((item) => item.classList.add('is-visible'));
        }

        const opening = document.getElementById('opening');
        document.getElementById('openInvitation').addEventListener('click', () => {
            opening.classList.add('is-hidden');
            document.body.style.overflow = '';
            document.querySelectorAll('.hero-copy, .hero-art').forEach((item) => {
                item.classList.add('is-visible');
            });
            document.getElementById('mempelai').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
        document.body.style.overflow = 'hidden';

        const countdown = document.getElementById('countdown');
        const eventTime = new Date(countdown.dataset.date).getTime();
        const updateCountdown = () => {
            const remaining = Math.max(0, eventTime - Date.now());
            const values = {
                days: Math.floor(remaining / 86400000),
                hours: Math.floor((remaining % 86400000) / 3600000),
                minutes: Math.floor((remaining % 3600000) / 60000),
                seconds: Math.floor((remaining % 60000) / 1000)
            };
            Object.entries(values).forEach(([unit, value]) => {
                countdown.querySelector(`[data-unit="${unit}"]`).textContent = String(value).padStart(2, '0');
            });
        };
        updateCountdown();
        window.setInterval(updateCountdown, 1000);

        const guestCountField = document.getElementById('guestCountField');
        const guestCountInput = document.getElementById('guest_count');
        const updateGuestCount = () => {
            const attending = document.querySelector('input[name="attendance"]:checked')?.value === 'attending';
            guestCountField.hidden = !attending;
            guestCountInput.required = attending;
        };
        document.querySelectorAll('input[name="attendance"]').forEach((input) => {
            input.addEventListener('change', updateGuestCount);
        });
        updateGuestCount();
    </script>
</body>
</html>
