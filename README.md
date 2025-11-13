# Urologia_project

Sistema de gestión de urología para hospital desarrollado con Laravel.

## Descripción

Este proyecto Laravel está diseñado para gestionar el departamento de urología de un hospital. Incluye funcionalidades para:

- **Gestión de Pacientes**: Registro y administración de información de pacientes
- **Gestión de Médicos**: Administración de médicos especialistas en urología
- **Gestión de Citas**: Programación y seguimiento de citas médicas
- **Historiales Médicos**: Registro de diagnósticos, tratamientos y recetas

## Características

### Modelos
- **Patient (Paciente)**: Información personal, datos de contacto, tipo de sangre, alergias
- **Doctor (Médico)**: Información profesional, especialidad, número de licencia
- **Appointment (Cita)**: Relación paciente-médico, fecha, estado, motivo
- **MedicalRecord (Historial Médico)**: Diagnóstico, tratamiento, recetas, resultados de laboratorio

### API REST
El sistema proporciona endpoints REST para todas las operaciones CRUD:

- `GET/POST /api/patients` - Listar/Crear pacientes
- `GET/PUT/DELETE /api/patients/{id}` - Ver/Actualizar/Eliminar paciente
- `GET/POST /api/doctors` - Listar/Crear médicos
- `GET/PUT/DELETE /api/doctors/{id}` - Ver/Actualizar/Eliminar médico
- `GET/POST /api/appointments` - Listar/Crear citas
- `GET/PUT/DELETE /api/appointments/{id}` - Ver/Actualizar/Eliminar cita
- `GET/POST /api/medical-records` - Listar/Crear historiales
- `GET/PUT/DELETE /api/medical-records/{id}` - Ver/Actualizar/Eliminar historial

## Instalación

1. Clonar el repositorio:
```bash
git clone https://github.com/Alejovg1997/Urologia_project.git
cd Urologia_project
```

2. Instalar dependencias:
```bash
composer install
```

3. Configurar variables de entorno:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurar base de datos en `.env`:
```
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database/database.sqlite
```

5. Ejecutar migraciones:
```bash
php artisan migrate
```

6. Iniciar servidor de desarrollo:
```bash
php artisan serve
```

## Requisitos

- PHP >= 8.3
- Composer
- SQLite o MySQL/PostgreSQL
- Laravel 12.x

## Estructura de la Base de Datos

### Tabla: patients
- Nombre completo
- Número de identificación
- Fecha de nacimiento
- Género
- Contacto (email, teléfono, dirección)
- Tipo de sangre
- Alergias

### Tabla: doctors
- Nombre completo
- Especialidad (por defecto: Urología)
- Número de licencia
- Educación
- Estado activo

### Tabla: appointments
- Relación con paciente y médico
- Fecha y hora de la cita
- Estado (programada, completada, cancelada, ausente)
- Motivo y notas

### Tabla: medical_records
- Relación con paciente, médico y cita
- Fecha del registro
- Diagnóstico
- Tratamiento
- Recetas
- Resultados de laboratorio
- Notas adicionales

## Uso de la API

### Ejemplo: Crear un paciente
```bash
curl -X POST http://localhost:8000/api/patients \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Juan",
    "last_name": "Pérez",
    "id_number": "12345678",
    "date_of_birth": "1980-01-01",
    "gender": "male",
    "email": "juan@example.com",
    "phone": "555-1234",
    "address": "Calle Principal 123"
  }'
```

### Ejemplo: Listar pacientes
```bash
curl http://localhost:8000/api/patients
```

## Tecnologías

- Laravel 12.x
- PHP 8.3
- SQLite/MySQL
- Laravel Sanctum (API Authentication)
- Eloquent ORM

## Licencia

Este proyecto es de código abierto.
