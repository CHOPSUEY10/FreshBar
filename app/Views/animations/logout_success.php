<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Freshbar') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background: #f3f7f6;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .transition-page {
            position: fixed;
            inset: 0;
            background: #f3f7f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .swipe-layer {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #08746d, #0f9f95);
            transform: translateY(-100%);
            animation: logoutSwipeDown 1.15s cubic-bezier(0.76, 0, 0.24, 1) forwards;
        }

        .soft-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.13);
            filter: blur(1px);
        }

        .soft-circle.one {
            width: 320px;
            height: 320px;
            top: -110px;
            right: -80px;
            animation: circleFloatDownOne 1.15s ease forwards;
        }

        .soft-circle.two {
            width: 220px;
            height: 220px;
            bottom: -70px;
            left: -60px;
            animation: circleFloatDownTwo 1.15s ease forwards;
        }

        .minimal-loader {
            position: relative;
            z-index: 3;
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: rgba(15, 159, 149, 0.12);
            border: 1px solid rgba(15, 159, 149, 0.22);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-34px) scale(0.86);
            opacity: 0;
            animation: loaderDown 0.95s cubic-bezier(0.76, 0, 0.24, 1) forwards;
            animation-delay: 0.14s;
        }

        .minimal-loader::before {
            content: "";
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 4px solid rgba(15, 159, 149, 0.22);
            border-top-color: #0f9f95;
            animation: spin 0.8s linear infinite;
        }

        .small-dot {
            position: absolute;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #ffffff;
            opacity: 0.9;
        }

        .small-dot.dot-one {
            top: 24%;
            left: 18%;
            animation: dotDown 1.1s ease forwards;
        }

        .small-dot.dot-two {
            bottom: 22%;
            right: 20%;
            animation: dotDown 1.1s ease forwards;
            animation-delay: 0.08s;
        }

        .small-dot.dot-three {
            top: 58%;
            right: 12%;
            animation: dotDown 1.1s ease forwards;
            animation-delay: 0.14s;
        }

        @keyframes logoutSwipeDown {
            0% {
                transform: translateY(-100%);
            }

            42% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(100%);
            }
        }

        @keyframes loaderDown {
            0% {
                opacity: 0;
                transform: translateY(-34px) scale(0.86);
            }

            35% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            100% {
                opacity: 0;
                transform: translateY(80px) scale(0.92);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes dotDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 0.9;
                transform: translateY(60px);
            }
        }

        @keyframes circleFloatDownOne {
            from {
                transform: translateY(-90px) scale(0.92);
            }

            to {
                transform: translateY(120px) scale(1);
            }
        }

        @keyframes circleFloatDownTwo {
            from {
                transform: translateY(-80px) scale(0.9);
            }

            to {
                transform: translateY(110px) scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="transition-page">
        <div class="swipe-layer">
            <div class="soft-circle one"></div>
            <div class="soft-circle two"></div>

            <span class="small-dot dot-one"></span>
            <span class="small-dot dot-two"></span>
            <span class="small-dot dot-three"></span>
        </div>

        <div class="minimal-loader"></div>
    </div>

    <script>
        setTimeout(function () {
            window.location.href = <?= json_encode($redirectUrl ?? site_url('login')) ?>;
        }, 1150);
    </script>
</body>
</html>