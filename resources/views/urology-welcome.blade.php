<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Urología</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 900px;
            width: 100%;
            padding: 50px;
        }
        h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        h2 {
            color: #764ba2;
            font-size: 1.3em;
            margin-bottom: 30px;
            text-align: center;
            font-weight: normal;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        .feature {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 3em;
            margin-bottom: 10px;
        }
        .feature h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .feature p {
            color: #666;
            font-size: 0.9em;
        }
        .api-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-top: 30px;
        }
        .api-section h3 {
            color: #667eea;
            margin-bottom: 15px;
        }
        .endpoint {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            border-left: 4px solid #667eea;
        }
        .method {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            margin-right: 10px;
        }
        .get { background: #28a745; color: white; }
        .post { background: #007bff; color: white; }
        .put { background: #ffc107; color: #333; }
        .delete { background: #dc3545; color: white; }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏥 Sistema de Gestión de Urología</h1>
        <h2>Plataforma integral para la gestión del departamento de urología</h2>
        
        <div class="features">
            <div class="feature">
                <div class="feature-icon">👥</div>
                <h3>Pacientes</h3>
                <p>Gestión completa de información de pacientes</p>
            </div>
            <div class="feature">
                <div class="feature-icon">⚕️</div>
                <h3>Médicos</h3>
                <p>Administración de especialistas en urología</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📅</div>
                <h3>Citas</h3>
                <p>Programación y seguimiento de citas médicas</p>
            </div>
            <div class="feature">
                <div class="feature-icon">📋</div>
                <h3>Historiales</h3>
                <p>Registros médicos completos y detallados</p>
            </div>
        </div>

        <div class="api-section">
            <h3>📡 API REST Endpoints</h3>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="method post">POST</span>
                /api/patients
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="method post">POST</span>
                /api/doctors
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="method post">POST</span>
                /api/appointments
            </div>
            
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="method post">POST</span>
                /api/medical-records
            </div>
        </div>

        <div class="footer">
            <p><strong>Tecnología:</strong> Laravel 12.x • PHP 8.3 • SQLite</p>
            <p style="margin-top: 10px;">Sistema desarrollado para la gestión hospitalaria de urología</p>
        </div>
    </div>
</body>
</html>
