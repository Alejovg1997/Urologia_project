<!doctype html>
<html>
<body>
    <p>Hola {{ $appointment->patient_name }},</p>
    <p>El estado de tu cita ha cambiado a: <strong>{{ $appointment->status }}</strong></p>

    @if($reason)
        <p>Motivo: {{ $reason }}</p>
    @endif

    <p>Detalles:</p>
    <ul>
        <li>Médico: {{ $appointment->doctor->name ?? 'N/A' }}</li>
        <li>Inicio: {{ $appointment->start_time }}</li>
        <li>Fin: {{ $appointment->end_time }}</li>
    </ul>

    <p>Saludos,<br/>Equipo Urologia</p>
</body>
</html>
