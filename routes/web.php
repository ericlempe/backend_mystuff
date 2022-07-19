<?php

use Illuminate\Support\Facades\Route;
use  Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

Route::get('/teste0/{numero}', function ($numero) {

    $filename = storage_path("d{$numero}.pdf");
    $file = fopen($filename, 'r');

    $response = Http::withBody($file, "application/pdf")->put('https://2d6a-2804-388-9050-52d9-cc04-6187-3a86-f67b.sa.ngrok.io/tika');
    $data = explode("\n", $response->body());
    dd($data);

    $creditos = [];
    $historico = [];
    $numeroInstalacaoUsina = null;
    $mesAno = null;
    $bolColetaCreditos = false;
    $bolColetaHistorico = false;
    foreach ($data as $indice => $linha) {
        if ($indice === 23) {
            $dados = explode(" ", $linha);
            $mesAno = $dados[0];
            $numeroInstalacaoUsina = $dados[1];
        }

        if ($linha === "Instalação Participação Geração Ativo (kWh)") {
            $bolColetaCreditos = true;
            continue;
        } elseif ($linha === "Atendimento EDP: 0800 721 0707") {
            $bolColetaCreditos = false;
            $bolColetaHistorico = false;
            continue;
        } elseif ($linha === "Histórico de Energia Injetada (kWh)") {
            $bolColetaCreditos = false;
            $bolColetaHistorico = true;
            continue;
        }

        if ($bolColetaCreditos && $linha !== "") {
            $creditos[] = explode(" ", $linha);
        }

        if ($bolColetaHistorico && !in_array($linha, ["", "Mês/ano Ativo (kWh)"])) {
            $historico[] = explode(" ", $linha);
        }

    }

    $arrayFinal["InstalacaoUsina"] = $numeroInstalacaoUsina;
    $arrayFinal["MesAno"] = $mesAno;
    $arrayFinal['creditos'] = $creditos;
    $arrayFinal['historico'] = $historico;

    dd($arrayFinal);

});

Route::get('/teste1/{numero}', function ($numero) {

    $filename = storage_path("d{$numero}.pdf");
    $text = (new \Spatie\PdfToText\Pdf())->setPdf($filename)->text();
    $data = explode("\n", $text);

    $numeroInstalacaoUsina = null;
    $dadosInstalacao = [];
    $dadosParticipacao = [];
    $dadosAtivo = [];

    $dadosMesAnoHistorico = [];
    $dadosAtivoHistorico = [];

    $bolGetInstalacao = false;
    $bolGetParticipacao = false;
    $bolGetAtivo = false;

    $bolGetMesAnoHistorico = false;
    $bolGetAtivoHistorico = false;

    foreach ($data as $key => $value) {

        if ($value === "Número da Instalação") {
            $numeroInstalacaoUsina = $data[$key + 4];
        }

        if ($value == "Total de créditos distribuidos no mês atual") {
            $bolGetInstalacao = true;
        } else if ($value == "Participação Geração") {
            $bolGetInstalacao = false;
        }

        if ($value == "Participação Geração") {
            $bolGetParticipacao = true;
        } else if ($value == "Ativo (kWh)") {
            $bolGetParticipacao = false;
        }

        if ($value == "Ativo (kWh)" && !$bolGetMesAnoHistorico) {
            $bolGetAtivo = true;
        } else if ($value == "Histórico de Energia Injetada (kWh)") {
            $bolGetAtivo = false;
        } else if ($value == "Atendimento EDP: 0800 721 0707") {
            $bolGetAtivo = false;
        }


        if ($value == "Histórico de Energia Injetada (kWh)") {
            $bolGetMesAnoHistorico = true;
        } else if ($value == "Ativo (kWh)" && !$bolGetAtivo) {
            $bolGetMesAnoHistorico = false;
        }

        if ($value == "Ativo (kWh)" && !$bolGetMesAnoHistorico && !$bolGetAtivo) {
            $bolGetAtivoHistorico = true;
        } else if ($value == "Atendimento EDP: 0800 721 0707" && $bolGetAtivoHistorico) {
            $bolGetAtivoHistorico = false;
        }

        if ($bolGetInstalacao && !in_array($value, ["", "Instalação", "Total de créditos distribuidos no mês atual"])) {
            $dadosInstalacao[] = $value;
        }

        if ($bolGetParticipacao && !in_array($value, ["", "kWh", "Participação Geração"])) {
            $dadosParticipacao[] = $value;
        }

        if ($bolGetAtivo && !in_array($value, ["", "Ativo (kWh)", "Histórico de Energia Injetada (kWh)"])) {
            $dadosAtivo[] = $value;
        }

        if ($bolGetMesAnoHistorico && !in_array($value, ["", "Mês/ano", "Histórico de Energia Injetada (kWh)", "kWh"])) {
            $dadosMesAnoHistorico[] = $value;
        }

        if ($bolGetAtivoHistorico && !in_array($value, ["", "Ativo (kWh)", "Atendimento EDP: 0800 721 0707"])) {
            $dadosAtivoHistorico[] = $value;
        }
    }

    $extrato = [];
    for ($i = 0; $i < count($dadosInstalacao); $i++) {
        $extrato[] = [
            "Instalacao" => $dadosInstalacao[$i],
            "Participacao" => $dadosParticipacao[$i],
            "Ativo" => $dadosAtivo[$i],
        ];
    }

    $historico = [];
    for ($i = 0; $i < count($dadosMesAnoHistorico); $i++) {
        $historico[] = [
            "mes/ano" => $dadosMesAnoHistorico[$i],
            "Ativo" => $dadosAtivoHistorico[$i],
        ];
    }

    $arrayFinal["InstalacaoUsina"] = $numeroInstalacaoUsina;
    $arrayFinal['extrato'] = $extrato;
    $arrayFinal['historico'] = $historico;

    dd($arrayFinal);

});

Route::get('/teste2/{numero}', function ($numero) {

    $file = storage_path("d{$numero}.pdf");

    $text = (new \Spatie\PdfToText\Pdf())->setPdf($file)->text();

    dd(explode("\n", $text));

});
