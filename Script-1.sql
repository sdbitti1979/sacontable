SELECT *
FROM public.usuarios;

SELECT idrol, descripcion
FROM public.roles;

SELECT idpermiso, descripcion
FROM public.permisos;

INSERT INTO public.permisos
(descripcion)
VALUES('USUARIOS.PDF');


SELECT idrolpermiso, rol_id, permiso_id
FROM public.roles_permisos;

insert into public.roles_permisos (rol_id, permiso_id) values(2, 1);

SELECT idclasificacion, nombre
FROM public.clasificaciones;

UPDATE public.usuarios
SET idrol=1, activo='T'
WHERE idusuario=10;

CREATE TABLE public.clasificaciones (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL  -- Activo, Pasivo, Patrimonio, Resultados
);

INSERT INTO public.clasificaciones
(nombre)
VALUES('NO APLICA');

SELECT id, nombre
FROM public.clasificaciones;

CREATE TABLE PUBLIC.cuentas (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,  -- Código único para la cuenta
    clasificacion VARCHAR(50) NOT NULL,  -- Activo, Pasivo, Patrimonio, Resultados
    saldo NUMERIC(15, 2) DEFAULT 0,      -- Saldo inicial de la cuenta
    id_padre INT REFERENCES PUBLIC.cuentas(id) ON DELETE SET NULL, -- Relación jerárquica
    utilizada BOOLEAN DEFAULT FALSE,     -- Indica si la cuenta fue utilizada en asientos
    eliminada BOOLEAN DEFAULT FALSE,     -- Para eliminación lógica
    modificado BOOLEAN DEFAULT FALSE,    -- Indica si puede ser modificado
    solo_admin BOOLEAN DEFAULT TRUE      -- Solo el administrador puede modificar
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Fecha de creación
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de última modificación
    usuario_id INT NOT NULL, -- Usuario que realizó la última modificación
    FOREIGN KEY (usuario_id) REFERENCES usuarios(idusuario)
);

drop table public.cuentas;

CREATE TABLE public.asientos_contables (
    id SERIAL PRIMARY KEY,
    cuenta_id INT REFERENCES cuentas(id) ON DELETE CASCADE,
    fecha DATE NOT NULL,
    descripcion TEXT,
    debe NUMERIC(15, 2),
    haber NUMERIC(15, 2)
);

ALTER TABLE asientos_contables
ADD COLUMN fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- Fecha de creación
ADD COLUMN fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de última modificación
ADD COLUMN usuario_id INT NOT NULL,  -- Usuario que realizó la última modificación
ADD CONSTRAINT fk_usuario_modificacion FOREIGN KEY (usuario_id) REFERENCES usuarios(idusuario);  -- Relación con la tabla usuarios

alter table cuentas 
alter column eliminada type char(1),
alter column modificado type char(1),
alter column solo_admin type char(1)

-- Fecha de creación
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de última modificación
    id_usuario_modificacion INT NOT NULL, -- Usuario que realizó la última modificación
    FOREIGN KEY (id_usuario_modificacion) REFERENCES usuarios(id)  -- 
ALTER TABLE public.cuentas ALTER COLUMN clasificacion_id TYPE integer USING clasificacion_id::integer;

INSERT INTO cuentas (nombre, codigo, clasificacion_id, id_padre, utilizada, eliminada, modificado, solo_admin, fecha_creacion, fecha_modificacion, usuario_id) VALUES 
('Dinero depositado en Banco Galicia', '13001', 1, NULL, 'F', 'F', 'F', 'T', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

SELECT idcuenta, nombre, codigo, clasificacion_id, saldo, id_padre, utilizada, eliminada, modificado, solo_admin, fecha_creacion, fecha_modificacion, usuario_id
FROM public.cuentas;

alter table public.cuentas add constraint fk_cuentas_clasificaciones foreign key (clasificacion_id) references public.clasificaciones(idclasificacion);

INSERT INTO public.cuentas
(nombre, codigo, clasificacion_id, saldo, id_padre, utilizada, eliminada, modificado, solo_admin, fecha_creacion, fecha_modificacion, usuario_id)
VALUES('', '', 0, 0, 0, 'F', 'F', 'F', 'T', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1);

SELECT idclasificacion, nombre
FROM public.clasificaciones;


INSERT INTO asientos_contables (cuenta_id, fecha, descripcion, debe, haber) 
VALUES 
(SELECT id FROM cuentas WHERE nombre = 'Banco Nación', '2024-09-17', 'Depósito de cheques', 3750, 0),
(SELECT id FROM cuentas WHERE nombre = 'Valores a depositar', '2024-09-17', 'Depósito de cheques', 0, 3750);

SELECT nombre, 
	   codigo, 
	   clasificacion_id, 
	   saldo, 
	   id_padre, 
	   utilizada, 
	   eliminada, 
	   modificado, 
	   solo_admin, 
	   fecha_creacion, 
	   fecha_modificacion, 
	   usuario_id
FROM public.cuentas;


alter table public.asientos_contables drop column haber;

create table public.asiento_cuenta(
	idasientocuenta serial primary key,
	asiento_id integer references asientos_contables(idasiento),
	cuenta_id integer references cuentas(idcuenta)
	
	
);


SELECT idclasificacion, nombre
FROM public.clasificaciones;

UPDATE public.clasificaciones
SET nombre='RESULTADO NEGATIVO'
WHERE idclasificacion=5;

SELECT id_auditoria, esquema, fecha, jsons, operacion, pk_campo, pk_valor, tabla, usuario
FROM public.audit_log;


CREATE TABLE audit_log (
    id_auditoria SERIAL PRIMARY KEY,            -- Identificador único de auditoría
    esquema VARCHAR(50) NOT NULL,               -- Esquema de la tabla afectada
    fecha TIMESTAMP DEFAULT now(),              -- Fecha y hora del evento de auditoría
    jsons JSONB,                                -- Datos en formato JSON
    operacion VARCHAR(50) NOT NULL,             -- Tipo de operación: 'INSERT', 'UPDATE', 'DELETE'
    pk_campo VARCHAR(50),                       -- Nombre del campo clave primaria
    pk_valor VARCHAR(50),                       -- Valor del campo clave primaria
    tabla VARCHAR(50) NOT NULL,                 -- Nombre de la tabla afectada
    usuario VARCHAR(20) NOT NULL                -- Usuario que realizó la operación
);

alter table public.asiento_cuenta add column haber float default null;
alter table public.usuarios drop column fecha_creacion;


CREATE OR REPLACE FUNCTION audit_trigger_function()
RETURNS TRIGGER AS $$
DECLARE
    pk_field_name TEXT;
    pk_value TEXT;
BEGIN
    -- Determinar el campo de clave primaria y su valor
    SELECT a.attname
    INTO pk_field_name
    FROM pg_index i
    JOIN pg_attribute a ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey)
    WHERE i.indrelid = TG_RELID AND i.indisprimary
    LIMIT 1;

    -- Obtener el valor de la clave primaria
    IF TG_OP = 'INSERT' THEN
        EXECUTE 'SELECT $1.' || pk_field_name INTO pk_value USING NEW;
    ELSE
        EXECUTE 'SELECT $1.' || pk_field_name INTO pk_value USING OLD;
    END IF;

    -- Registrar la operación de auditoría
    IF TG_OP = 'INSERT' THEN
        INSERT INTO audit_log (esquema, tabla, operacion, pk_campo, pk_valor, jsons, usuario)
        VALUES (TG_TABLE_SCHEMA, TG_TABLE_NAME, 'INSERT', pk_field_name, pk_value, row_to_json(NEW)::jsonb, current_user);
        RETURN NEW;
    ELSIF (TG_OP = 'UPDATE') THEN
        INSERT INTO audit_log (esquema, tabla, operacion, pk_campo, pk_valor, jsons, usuario)
        VALUES (TG_TABLE_SCHEMA, TG_TABLE_NAME, 'UPDATE', pk_field_name, pk_value, jsonb_build_object('old', row_to_json(OLD), 'new', row_to_json(NEW)), current_user);
        RETURN NEW;
    ELSIF (TG_OP = 'DELETE') THEN
        INSERT INTO audit_log (esquema, tabla, operacion, pk_campo, pk_valor, jsons, usuario)
        VALUES (TG_TABLE_SCHEMA, TG_TABLE_NAME, 'DELETE', pk_field_name, pk_value, row_to_json(OLD)::jsonb, current_user);
        RETURN OLD;
    END IF;
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;


-- Crear el trigger en la tabla 'productos'
CREATE TRIGGER audit_trigger
AFTER INSERT OR UPDATE OR DELETE ON bancos
FOR EACH ROW EXECUTE FUNCTION audit_trigger_function();

SELECT *
FROM public.usuarios;

UPDATE public.usuarios
SET  activo='T'
WHERE idusuario=13;

create table public.bancos(
	idbanco serial primary key,
	descripcion varchar(200) not null,
	activo char(1) not null default 'T'
);
