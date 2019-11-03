<style>
div {
    margin-top: 20px;
    margin-bottom: 20px;
}
</style>
<div>
    Oi {{ $payer }},
</div>
<div>
    Sua transação para {{ $payee }} no valor de R$ {{ number_format($value, 2, ',', '.') }}, foi concluída com sucesso!
</div>
<div>
    Qualquer dúvida estamos a disposição.
</div>
<div>
    Abraços,
</div>
<div>
    Equipe PicPay
</div>