CREATE DATABASE ;
USE ;

-- Tabla Usuarios (Clientes y Administrador)
CREATE TABLE Usuarios (
    UsuarioID INT AUTO_INCREMENT PRIMARY KEY,
    Usuario_Nombre VARCHAR(50) NOT NULL,
    Usuario_Apellidos VARCHAR(50) NOT NULL,
    Usuario_Telefono VARCHAR(10) NOT NULL UNIQUE,
    Usuario_Email VARCHAR(100) NOT NULL,
    Usuario_Contraseña VARCHAR(255) NOT NULL, 
    Usuario_Genero ENUM('Masculino', 'Femenino', 'Otro')
    Usuario_FechaNacimiento DATE, 
    Usuario_Direccion VARCHAR(255),
    Usuario_Ciudad VARCHAR(50),
    Usuario_Estado VARCHAR(50),

    Usuario_Tarjeta VARCHAR(20) NOT NULL UNIQUE,
    Usuario_Puntos INT NOT NULL DEFAULT 0,

    Usuario_Rol ENUM('Administrador', 'Cliente') NOT NULL DEFAULT 'Cliente',
    Usuario_FechaCreacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Usuario_EstadoCuenta ENUM('Activo', 'Suspendido') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Tabla Premios (Catálogo de canje)
CREATE TABLE Premios (
    PremioID INT AUTO_INCREMENT PRIMARY KEY,
    Premio_Nombre VARCHAR(100) NOT NULL,
    Premio_Descripcion TEXT,
    Premio_PuntosNecesarios INT NOT NULL,
    Premio_Disponible BOOLEAN NOT NULL DEFAULT TRUE,
    Premio_Imagen VARCHAR(255),
    Premio_FechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla Beneficios (Empresas en convenio)
CREATE TABLE Beneficios (
    BeneficioID INT AUTO_INCREMENT PRIMARY KEY,
    Empresa_Nombre VARCHAR(100) NOT NULL,
    Beneficio_Descripcion TEXT,
    Beneficio_Activo BOOLEAN NOT NULL DEFAULT TRUE,
    Beneficio_FechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla Canjes (Registro de premios canjeados)
CREATE TABLE Canjes (
    CanjeID INT AUTO_INCREMENT PRIMARY KEY,
    UsuarioID INT NOT NULL,
    PremioID INT NOT NULL,
    FechaCanje DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UsuarioID) REFERENCES Usuarios(UsuarioID) ON DELETE CASCADE,
    FOREIGN KEY (PremioID) REFERENCES Premios(PremioID) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

