<!doctype html>
<html>
<body>
    <p>Hola {{ $appointment->patient_name }},</p>
    <p>Tu cita ha sido solicitada correctamente con los siguientes datos:</p>
    <ul>
        <li>Médico: {{ $appointment->doctor->name ?? 'N/A' }}</li>
        <li>Inicio: {{ $appointment->start_time }}</li>
        <li>Fin: {{ $appointment->end_time }}</li>
        <li>Estado: {{ $appointment->status }}</li>
    </ul>

    <p>Te notificaremos cuando la cita sea revisada.</p>

    <p>Saludos,<br/>Equipo Urologia</p>
</body>
</html>
