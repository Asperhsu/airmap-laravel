<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Warning</title>
    <link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body class="bg-dark" style="overflow-x: hidden;">
    <div class="d-flex justify-content-center align-items-center" style="width: 100vw; height: 100vh">
        <div class="col-11 col-md-6 jumbotron">
            <h1 class="display-5 text-center">請注意</h1>

            <ol class="pl-2">
                <li class="mb-2">
                    微型感測器與標準測站的感測值，因為兩者感測原理不同，感測數值自然不相同，迄今仍無良好的系統校正方式，因此兩者的數值不宜直接進行比較。
                </li>
                <li class="mb-2">
                    根據目前已知的研究報告，微型感測器的感測數值在精確度上具有極高的一致性，且與標準測站的感測值亦有相同的趨勢表現，但在準確度方面，兩者間的誤差會隨著環境因素變化而產生極大的差異。
                </l>
                <li class="mb-2">
                    本系統目前只負責資料之彙整與呈現，使用本系統所提供之各項資訊時，需謹守「趨勢比對」、「相對判斷」的原則，切勿過度解讀闡釋，以免產生其他爭議，有關空氣品質相關資訊，一切仍應以官方數據為準。
                </li>
            </ol>

            <hr class="my-4">

            <div class="w-75 mx-auto">
                <form method="POST" action="/v5/accept-warning" class="mb-3">
                    {{ csrf_field() }}
                    <button class="btn btn-secondary btn-block" type="submit">我了解，我不恐慌</button>
                </form>

                <a href="https://taqm.epa.gov.tw/taqm/tw/default.aspx" class="btn btn-block btn-secondary">
                    我不懂，我感到恐慌
                </a>
            </div>


        </div>

    </div>
</body>
</html>