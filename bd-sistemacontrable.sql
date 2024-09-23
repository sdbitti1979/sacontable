--
-- PostgreSQL database dump
--

-- Dumped from database version 17rc1
-- Dumped by pg_dump version 17rc1

-- Started on 2024-09-22 23:37:10

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE "SA_DESARROLLO";
--
-- TOC entry 4900 (class 1262 OID 16388)
-- Name: SA_DESARROLLO; Type: DATABASE; Schema: -; Owner: -
--

CREATE DATABASE "SA_DESARROLLO" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Argentina.1252';


\connect "SA_DESARROLLO"

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 5 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- TOC entry 4901 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- TOC entry 285 (class 1255 OID 24784)
-- Name: audit_trigger_function(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.audit_trigger_function() RETURNS trigger
    LANGUAGE plpgsql
    AS $_$
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
$_$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 233 (class 1259 OID 24732)
-- Name: asiento_cuenta; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asiento_cuenta (
    idasientocuenta integer NOT NULL,
    asiento_id integer,
    cuenta_id integer,
    debe double precision,
    haber double precision
);


--
-- TOC entry 232 (class 1259 OID 24731)
-- Name: asiento_cuenta_idasientocuenta_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asiento_cuenta_idasientocuenta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4902 (class 0 OID 0)
-- Dependencies: 232
-- Name: asiento_cuenta_idasientocuenta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asiento_cuenta_idasientocuenta_seq OWNED BY public.asiento_cuenta.idasientocuenta;


--
-- TOC entry 231 (class 1259 OID 24680)
-- Name: asientos_contables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.asientos_contables (
    idasiento integer NOT NULL,
    cuenta_id integer,
    fecha date NOT NULL,
    descripcion text,
    usuario_id integer NOT NULL
);


--
-- TOC entry 230 (class 1259 OID 24679)
-- Name: asientos_contables_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.asientos_contables_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4903 (class 0 OID 0)
-- Dependencies: 230
-- Name: asientos_contables_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.asientos_contables_id_seq OWNED BY public.asientos_contables.idasiento;


--
-- TOC entry 235 (class 1259 OID 24775)
-- Name: audit_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.audit_log (
    id_auditoria integer NOT NULL,
    esquema character varying(50) NOT NULL,
    fecha timestamp without time zone DEFAULT now(),
    jsons jsonb,
    operacion character varying(50) NOT NULL,
    pk_campo character varying(50),
    pk_valor character varying(50),
    tabla character varying(50) NOT NULL,
    usuario character varying(20) NOT NULL
);


--
-- TOC entry 234 (class 1259 OID 24774)
-- Name: audit_log_id_auditoria_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.audit_log_id_auditoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4904 (class 0 OID 0)
-- Dependencies: 234
-- Name: audit_log_id_auditoria_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.audit_log_id_auditoria_seq OWNED BY public.audit_log.id_auditoria;


--
-- TOC entry 237 (class 1259 OID 24792)
-- Name: bancos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bancos (
    idbanco integer NOT NULL,
    descripcion character varying(200) NOT NULL,
    activo character(1) DEFAULT 'T'::bpchar NOT NULL
);


--
-- TOC entry 236 (class 1259 OID 24791)
-- Name: bancos_idbanco_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bancos_idbanco_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4905 (class 0 OID 0)
-- Dependencies: 236
-- Name: bancos_idbanco_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bancos_idbanco_seq OWNED BY public.bancos.idbanco;


--
-- TOC entry 227 (class 1259 OID 24652)
-- Name: clasificaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clasificaciones (
    idclasificacion integer NOT NULL,
    nombre character varying(50) NOT NULL
);


--
-- TOC entry 226 (class 1259 OID 24651)
-- Name: clasificaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clasificaciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4906 (class 0 OID 0)
-- Dependencies: 226
-- Name: clasificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clasificaciones_id_seq OWNED BY public.clasificaciones.idclasificacion;


--
-- TOC entry 229 (class 1259 OID 24661)
-- Name: cuentas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cuentas (
    idcuenta integer NOT NULL,
    nombre character varying(255) NOT NULL,
    codigo character varying(50) NOT NULL,
    clasificacion_id integer NOT NULL,
    saldo numeric(15,2) DEFAULT 0,
    id_padre integer,
    utilizada character(1) DEFAULT false,
    eliminada character(1) DEFAULT false,
    modificado character(1) DEFAULT false,
    solo_admin character(1) DEFAULT true,
    usuario_id integer NOT NULL
);


--
-- TOC entry 228 (class 1259 OID 24660)
-- Name: cuentas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cuentas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4907 (class 0 OID 0)
-- Dependencies: 228
-- Name: cuentas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cuentas_id_seq OWNED BY public.cuentas.idcuenta;


--
-- TOC entry 223 (class 1259 OID 24589)
-- Name: permisos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permisos (
    idpermiso integer NOT NULL,
    descripcion character varying(100) NOT NULL
);


--
-- TOC entry 222 (class 1259 OID 24588)
-- Name: permisos_idpermiso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permisos_idpermiso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4908 (class 0 OID 0)
-- Dependencies: 222
-- Name: permisos_idpermiso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permisos_idpermiso_seq OWNED BY public.permisos.idpermiso;


--
-- TOC entry 221 (class 1259 OID 24582)
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    idrol integer NOT NULL,
    descripcion character varying(100) NOT NULL
);


--
-- TOC entry 220 (class 1259 OID 24581)
-- Name: roles_idrol_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_idrol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4909 (class 0 OID 0)
-- Dependencies: 220
-- Name: roles_idrol_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_idrol_seq OWNED BY public.roles.idrol;


--
-- TOC entry 225 (class 1259 OID 24596)
-- Name: roles_permisos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles_permisos (
    idrolpermiso integer NOT NULL,
    rol_id integer NOT NULL,
    permiso_id integer NOT NULL
);


--
-- TOC entry 224 (class 1259 OID 24595)
-- Name: roles_permisos_idrolpermiso_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_permisos_idrolpermiso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4910 (class 0 OID 0)
-- Dependencies: 224
-- Name: roles_permisos_idrolpermiso_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_permisos_idrolpermiso_seq OWNED BY public.roles_permisos.idrolpermiso;


--
-- TOC entry 219 (class 1259 OID 16396)
-- Name: usuarios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.usuarios (
    idusuario integer NOT NULL,
    usuario character varying(15) NOT NULL,
    email character varying(100) NOT NULL,
    clave character varying(255) NOT NULL,
    apellido character varying(30) NOT NULL,
    nombre character varying(30) NOT NULL,
    ultimo_inicio_sesion timestamp without time zone,
    cuil character varying(11),
    idrol integer,
    activo character(1)
);


--
-- TOC entry 218 (class 1259 OID 16395)
-- Name: usuarios_idusuario_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.usuarios_idusuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- TOC entry 4911 (class 0 OID 0)
-- Dependencies: 218
-- Name: usuarios_idusuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.usuarios_idusuario_seq OWNED BY public.usuarios.idusuario;


--
-- TOC entry 4685 (class 2604 OID 24735)
-- Name: asiento_cuenta idasientocuenta; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asiento_cuenta ALTER COLUMN idasientocuenta SET DEFAULT nextval('public.asiento_cuenta_idasientocuenta_seq'::regclass);


--
-- TOC entry 4684 (class 2604 OID 24683)
-- Name: asientos_contables idasiento; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asientos_contables ALTER COLUMN idasiento SET DEFAULT nextval('public.asientos_contables_id_seq'::regclass);


--
-- TOC entry 4686 (class 2604 OID 24778)
-- Name: audit_log id_auditoria; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_log ALTER COLUMN id_auditoria SET DEFAULT nextval('public.audit_log_id_auditoria_seq'::regclass);


--
-- TOC entry 4688 (class 2604 OID 24795)
-- Name: bancos idbanco; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bancos ALTER COLUMN idbanco SET DEFAULT nextval('public.bancos_idbanco_seq'::regclass);


--
-- TOC entry 4677 (class 2604 OID 24655)
-- Name: clasificaciones idclasificacion; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clasificaciones ALTER COLUMN idclasificacion SET DEFAULT nextval('public.clasificaciones_id_seq'::regclass);


--
-- TOC entry 4678 (class 2604 OID 24664)
-- Name: cuentas idcuenta; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cuentas ALTER COLUMN idcuenta SET DEFAULT nextval('public.cuentas_id_seq'::regclass);


--
-- TOC entry 4675 (class 2604 OID 24592)
-- Name: permisos idpermiso; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permisos ALTER COLUMN idpermiso SET DEFAULT nextval('public.permisos_idpermiso_seq'::regclass);


--
-- TOC entry 4674 (class 2604 OID 24585)
-- Name: roles idrol; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN idrol SET DEFAULT nextval('public.roles_idrol_seq'::regclass);


--
-- TOC entry 4676 (class 2604 OID 24599)
-- Name: roles_permisos idrolpermiso; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles_permisos ALTER COLUMN idrolpermiso SET DEFAULT nextval('public.roles_permisos_idrolpermiso_seq'::regclass);


--
-- TOC entry 4673 (class 2604 OID 16399)
-- Name: usuarios idusuario; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios ALTER COLUMN idusuario SET DEFAULT nextval('public.usuarios_idusuario_seq'::regclass);


--
-- TOC entry 4890 (class 0 OID 24732)
-- Dependencies: 233
-- Data for Name: asiento_cuenta; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4888 (class 0 OID 24680)
-- Dependencies: 231
-- Data for Name: asientos_contables; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4892 (class 0 OID 24775)
-- Dependencies: 235
-- Data for Name: audit_log; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.audit_log VALUES (1, 'public', '2024-09-20 10:54:02.705679', '{"new": {"nombre": "RESULTADO POSITIVO", "idclasificacion": 4}, "old": {"nombre": "RESULTADOS", "idclasificacion": 4}}', 'UPDATE', 'idclasificacion', '4', 'clasificaciones', 'postgres');
INSERT INTO public.audit_log VALUES (2, 'public', '2024-09-20 10:54:33.462937', '{"new": {"nombre": "RESULTADO NEGATIVO", "idclasificacion": 5}, "old": {"nombre": "NO APLICA", "idclasificacion": 5}}', 'UPDATE', 'idclasificacion', '5', 'clasificaciones', 'postgres');
INSERT INTO public.audit_log VALUES (3, 'public', '2024-09-20 10:55:47.009588', '{"nombre": "NO APLICA", "idclasificacion": 6}', 'INSERT', 'idclasificacion', '6', 'clasificaciones', 'postgres');
INSERT INTO public.audit_log VALUES (4, 'public', '2024-09-20 13:01:07.378162', '{"new": {"cuil": "20274416603", "clave": "$2y$12$MNtER1cWK6FWsqtweTxqzuwMb0qXRj7qlHiMAGP/M4dHoOiwFTvYu", "email": "sdbitti@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "SERGIO DARIO", "usuario": "sdbitti", "apellido": "BITTI", "idusuario": 1, "ultimo_inicio_sesion": "2024-09-20T13:01:07"}, "old": {"cuil": "20274416603", "clave": "$2y$12$MNtER1cWK6FWsqtweTxqzuwMb0qXRj7qlHiMAGP/M4dHoOiwFTvYu", "email": "sdbitti@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "SERGIO DARIO", "usuario": "sdbitti", "apellido": "BITTI", "idusuario": 1, "ultimo_inicio_sesion": "2024-09-19T20:18:35"}}', 'UPDATE', 'idusuario', '1', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (5, 'public', '2024-09-20 14:01:18.23717', '{"cuil": "27217154117", "clave": "$2y$12$0gd5cLDAu7RlZia/3c6SX.AbWvDnrE40qKkTNRzbHuxeUMucvR95e", "email": "vmangini@comunidad.unnoba.edu.ar", "idrol": 1, "activo": null, "nombre": "VERONICA", "usuario": "vmangini", "apellido": "MANGINI", "idusuario": 11, "ultimo_inicio_sesion": "2024-09-20T17:01:18"}', 'INSERT', 'idusuario', '11', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (6, 'public', '2024-09-20 14:07:37.733517', '{"cuil": "27248479928", "clave": "$2y$12$7G2uGA210XAaBxVijaFdPeskBOFU.0sj2KluoI0wZbyKtOyuXSSEO", "email": "cmoralejo@comunidad.unnoba.edu.ar", "idrol": 1, "activo": null, "nombre": "CARLA", "usuario": "cmoralejo", "apellido": "MORALEJO", "idusuario": 12, "ultimo_inicio_sesion": "2024-09-20T17:07:37"}', 'INSERT', 'idusuario', '12', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (7, 'public', '2024-09-20 14:11:35.425214', '{"new": {"cuil": "27248479928", "clave": "$2y$12$7G2uGA210XAaBxVijaFdPeskBOFU.0sj2KluoI0wZbyKtOyuXSSEO", "email": "cmoralejo@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "CARLA", "usuario": "cmoralejo", "apellido": "MORALEJO", "idusuario": 12, "ultimo_inicio_sesion": "2024-09-20T17:07:37"}, "old": {"cuil": "27248479928", "clave": "$2y$12$7G2uGA210XAaBxVijaFdPeskBOFU.0sj2KluoI0wZbyKtOyuXSSEO", "email": "cmoralejo@comunidad.unnoba.edu.ar", "idrol": 1, "activo": null, "nombre": "CARLA", "usuario": "cmoralejo", "apellido": "MORALEJO", "idusuario": 12, "ultimo_inicio_sesion": "2024-09-20T17:07:37"}}', 'UPDATE', 'idusuario', '12', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (8, 'public', '2024-09-20 14:11:57.273016', '{"new": {"cuil": "27217154117", "clave": "$2y$12$0gd5cLDAu7RlZia/3c6SX.AbWvDnrE40qKkTNRzbHuxeUMucvR95e", "email": "vmangini@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "VERONICA", "usuario": "vmangini", "apellido": "MANGINI", "idusuario": 11, "ultimo_inicio_sesion": "2024-09-20T17:01:18"}, "old": {"cuil": "27217154117", "clave": "$2y$12$0gd5cLDAu7RlZia/3c6SX.AbWvDnrE40qKkTNRzbHuxeUMucvR95e", "email": "vmangini@comunidad.unnoba.edu.ar", "idrol": 1, "activo": null, "nombre": "VERONICA", "usuario": "vmangini", "apellido": "MANGINI", "idusuario": 11, "ultimo_inicio_sesion": "2024-09-20T17:01:18"}}', 'UPDATE', 'idusuario', '11', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (9, 'public', '2024-09-20 14:18:44.696632', '{"cuil": "23219868499", "clave": "$2y$12$f77RTbtlf1jaoUdbOUsw0.1JnorjVM2Sd6.fl4FTze/a/3blyP9Wm", "email": "fbalbi@comunidad.unnoba.edu.ar", "idrol": 1, "activo": null, "nombre": "FERNANDO", "usuario": "fbalbi", "apellido": "BALBI", "idusuario": 13, "ultimo_inicio_sesion": "2024-09-20T17:18:44"}', 'INSERT', 'idusuario', '13', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (10, 'public', '2024-09-20 14:20:28.988254', '{"new": {"cuil": "23219868499", "clave": "$2y$12$f77RTbtlf1jaoUdbOUsw0.1JnorjVM2Sd6.fl4FTze/a/3blyP9Wm", "email": "fbalbi@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "FERNANDO", "usuario": "fbalbi", "apellido": "BALBI", "idusuario": 13, "ultimo_inicio_sesion": "2024-09-20T17:18:44"}, "old": {"cuil": "23219868499", "clave": "$2y$12$f77RTbtlf1jaoUdbOUsw0.1JnorjVM2Sd6.fl4FTze/a/3blyP9Wm", "email": "fbalbi@comunidad.unnoba.edu.ar", "idrol": 1, "activo": null, "nombre": "FERNANDO", "usuario": "fbalbi", "apellido": "BALBI", "idusuario": 13, "ultimo_inicio_sesion": "2024-09-20T17:18:44"}}', 'UPDATE', 'idusuario', '13', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (11, 'public', '2024-09-20 14:23:40.32499', '{"cuil": "20327813332", "clave": "$2y$12$uC.VdDs6KdVGMYVPZni/2OpDYDg4IxR2BQXCt9Y8a3pEZd.jMzyJG", "email": "laispuru@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "LEANDRO", "usuario": "laispuru", "apellido": "AISPURU", "idusuario": 14, "ultimo_inicio_sesion": "2024-09-20T17:23:40"}', 'INSERT', 'idusuario', '14', 'usuarios', 'postgres');
INSERT INTO public.audit_log VALUES (12, 'public', '2024-09-20 18:46:47.255624', '{"new": {"cuil": "20274416603", "clave": "$2y$12$MNtER1cWK6FWsqtweTxqzuwMb0qXRj7qlHiMAGP/M4dHoOiwFTvYu", "email": "sdbitti@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "SERGIO DARIO", "usuario": "sdbitti", "apellido": "BITTI", "idusuario": 1, "ultimo_inicio_sesion": "2024-09-20T18:46:47"}, "old": {"cuil": "20274416603", "clave": "$2y$12$MNtER1cWK6FWsqtweTxqzuwMb0qXRj7qlHiMAGP/M4dHoOiwFTvYu", "email": "sdbitti@comunidad.unnoba.edu.ar", "idrol": 1, "activo": "T", "nombre": "SERGIO DARIO", "usuario": "sdbitti", "apellido": "BITTI", "idusuario": 1, "ultimo_inicio_sesion": "2024-09-20T13:01:07"}}', 'UPDATE', 'idusuario', '1', 'usuarios', 'postgres');


--
-- TOC entry 4894 (class 0 OID 24792)
-- Dependencies: 237
-- Data for Name: bancos; Type: TABLE DATA; Schema: public; Owner: -
--



--
-- TOC entry 4884 (class 0 OID 24652)
-- Dependencies: 227
-- Data for Name: clasificaciones; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.clasificaciones VALUES (1, 'ACTIVO');
INSERT INTO public.clasificaciones VALUES (2, 'PASIVO');
INSERT INTO public.clasificaciones VALUES (3, 'PATRIMONIO');
INSERT INTO public.clasificaciones VALUES (4, 'RESULTADO POSITIVO');
INSERT INTO public.clasificaciones VALUES (5, 'RESULTADO NEGATIVO');
INSERT INTO public.clasificaciones VALUES (6, 'NO APLICA');


--
-- TOC entry 4886 (class 0 OID 24661)
-- Dependencies: 229
-- Data for Name: cuentas; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.cuentas VALUES (3, 'Capital Social', '1001', 3, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (4, 'Inmuebles', '2001', 1, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (5, 'Mercaderías en existencia', '3001', 1, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (6, 'Acreedores', '4001', 2, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (7, 'Audi A4 (no aplica)', '5001', 5, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (8, 'Mobiliario', '6001', 1, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (9, 'Papelería y útiles', '7001', 1, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (10, 'Pagarés', '8001', 2, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (11, 'Spot publicitario', '9001', 1, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (12, 'Sueldos a pagar', '10001', 2, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (13, 'Resultados acumulados', '12001', 3, 0.00, NULL, 'F', 'F', 'F', 'T', 1);
INSERT INTO public.cuentas VALUES (14, 'Dinero depositado en Banco Galicia', '13001', 1, 0.00, NULL, 'F', 'F', 'F', 'T', 1);


--
-- TOC entry 4880 (class 0 OID 24589)
-- Dependencies: 223
-- Data for Name: permisos; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.permisos VALUES (1, 'USUARIOS.CONSULTAR');
INSERT INTO public.permisos VALUES (2, 'USUARIOS.CREAR');
INSERT INTO public.permisos VALUES (3, 'USUARIOS.EDITAR');
INSERT INTO public.permisos VALUES (4, 'USUARIOS.ELIMINAR');
INSERT INTO public.permisos VALUES (5, 'USUARIOS.EXCEL');
INSERT INTO public.permisos VALUES (6, 'USUARIOS.PDF');


--
-- TOC entry 4878 (class 0 OID 24582)
-- Dependencies: 221
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.roles VALUES (1, 'ADMINISTRADOR');
INSERT INTO public.roles VALUES (2, 'OPERADOR');


--
-- TOC entry 4882 (class 0 OID 24596)
-- Dependencies: 225
-- Data for Name: roles_permisos; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.roles_permisos VALUES (1, 1, 1);
INSERT INTO public.roles_permisos VALUES (2, 1, 2);
INSERT INTO public.roles_permisos VALUES (3, 1, 3);
INSERT INTO public.roles_permisos VALUES (4, 1, 4);
INSERT INTO public.roles_permisos VALUES (5, 1, 5);
INSERT INTO public.roles_permisos VALUES (6, 1, 6);
INSERT INTO public.roles_permisos VALUES (7, 2, 1);


--
-- TOC entry 4876 (class 0 OID 16396)
-- Dependencies: 219
-- Data for Name: usuarios; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.usuarios VALUES (12, 'cmoralejo', 'cmoralejo@comunidad.unnoba.edu.ar', '$2y$12$7G2uGA210XAaBxVijaFdPeskBOFU.0sj2KluoI0wZbyKtOyuXSSEO', 'MORALEJO', 'CARLA', '2024-09-20 17:07:37', '27248479928', 1, 'T');
INSERT INTO public.usuarios VALUES (11, 'vmangini', 'vmangini@comunidad.unnoba.edu.ar', '$2y$12$0gd5cLDAu7RlZia/3c6SX.AbWvDnrE40qKkTNRzbHuxeUMucvR95e', 'MANGINI', 'VERONICA', '2024-09-20 17:01:18', '27217154117', 1, 'T');
INSERT INTO public.usuarios VALUES (13, 'fbalbi', 'fbalbi@comunidad.unnoba.edu.ar', '$2y$12$f77RTbtlf1jaoUdbOUsw0.1JnorjVM2Sd6.fl4FTze/a/3blyP9Wm', 'BALBI', 'FERNANDO', '2024-09-20 17:18:44', '23219868499', 1, 'T');
INSERT INTO public.usuarios VALUES (14, 'laispuru', 'laispuru@comunidad.unnoba.edu.ar', '$2y$12$uC.VdDs6KdVGMYVPZni/2OpDYDg4IxR2BQXCt9Y8a3pEZd.jMzyJG', 'AISPURU', 'LEANDRO', '2024-09-20 17:23:40', '20327813332', 1, 'T');
INSERT INTO public.usuarios VALUES (1, 'sdbitti', 'sdbitti@comunidad.unnoba.edu.ar', '$2y$12$MNtER1cWK6FWsqtweTxqzuwMb0qXRj7qlHiMAGP/M4dHoOiwFTvYu', 'BITTI', 'SERGIO DARIO', '2024-09-20 18:46:47', '20274416603', 1, 'T');
INSERT INTO public.usuarios VALUES (8, 'ydelgado', 'yldelgadocarrasco@comunidad.unnoba.edu.ar', '$2y$12$ARxYwtOCaJCdfJ7MdmE3kuGidC3/9TT4iF4UWasSYXkzZQ66gIbEG', 'DELGADO', 'YEMINA', '2024-09-11 16:59:06', '23423423423', 2, 'T');
INSERT INTO public.usuarios VALUES (2, 'emoreyra', 'emoreyra@comunida.unnoba.edu.ar', '123', 'MOREYRA', 'ELOY', '2024-09-10 14:50:40.252521', '20404563983', 2, 'T');


--
-- TOC entry 4912 (class 0 OID 0)
-- Dependencies: 232
-- Name: asiento_cuenta_idasientocuenta_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.asiento_cuenta_idasientocuenta_seq', 1, false);


--
-- TOC entry 4913 (class 0 OID 0)
-- Dependencies: 230
-- Name: asientos_contables_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.asientos_contables_id_seq', 1, false);


--
-- TOC entry 4914 (class 0 OID 0)
-- Dependencies: 234
-- Name: audit_log_id_auditoria_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.audit_log_id_auditoria_seq', 12, true);


--
-- TOC entry 4915 (class 0 OID 0)
-- Dependencies: 236
-- Name: bancos_idbanco_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.bancos_idbanco_seq', 1, false);


--
-- TOC entry 4916 (class 0 OID 0)
-- Dependencies: 226
-- Name: clasificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.clasificaciones_id_seq', 6, true);


--
-- TOC entry 4917 (class 0 OID 0)
-- Dependencies: 228
-- Name: cuentas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.cuentas_id_seq', 15, true);


--
-- TOC entry 4918 (class 0 OID 0)
-- Dependencies: 222
-- Name: permisos_idpermiso_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.permisos_idpermiso_seq', 6, true);


--
-- TOC entry 4919 (class 0 OID 0)
-- Dependencies: 220
-- Name: roles_idrol_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_idrol_seq', 2, true);


--
-- TOC entry 4920 (class 0 OID 0)
-- Dependencies: 224
-- Name: roles_permisos_idrolpermiso_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_permisos_idrolpermiso_seq', 7, true);


--
-- TOC entry 4921 (class 0 OID 0)
-- Dependencies: 218
-- Name: usuarios_idusuario_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.usuarios_idusuario_seq', 14, true);


--
-- TOC entry 4709 (class 2606 OID 24737)
-- Name: asiento_cuenta asiento_cuenta_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asiento_cuenta
    ADD CONSTRAINT asiento_cuenta_pkey PRIMARY KEY (idasientocuenta);


--
-- TOC entry 4707 (class 2606 OID 24687)
-- Name: asientos_contables asientos_contables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asientos_contables
    ADD CONSTRAINT asientos_contables_pkey PRIMARY KEY (idasiento);


--
-- TOC entry 4711 (class 2606 OID 24783)
-- Name: audit_log audit_log_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_log
    ADD CONSTRAINT audit_log_pkey PRIMARY KEY (id_auditoria);


--
-- TOC entry 4713 (class 2606 OID 24798)
-- Name: bancos bancos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bancos
    ADD CONSTRAINT bancos_pkey PRIMARY KEY (idbanco);


--
-- TOC entry 4699 (class 2606 OID 24659)
-- Name: clasificaciones clasificaciones_nombre_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clasificaciones
    ADD CONSTRAINT clasificaciones_nombre_key UNIQUE (nombre);


--
-- TOC entry 4701 (class 2606 OID 24657)
-- Name: clasificaciones clasificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clasificaciones
    ADD CONSTRAINT clasificaciones_pkey PRIMARY KEY (idclasificacion);


--
-- TOC entry 4703 (class 2606 OID 24673)
-- Name: cuentas cuentas_codigo_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT cuentas_codigo_key UNIQUE (codigo);


--
-- TOC entry 4705 (class 2606 OID 24671)
-- Name: cuentas cuentas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT cuentas_pkey PRIMARY KEY (idcuenta);


--
-- TOC entry 4695 (class 2606 OID 24594)
-- Name: permisos permisos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permisos
    ADD CONSTRAINT permisos_pkey PRIMARY KEY (idpermiso);


--
-- TOC entry 4697 (class 2606 OID 24601)
-- Name: roles_permisos roles_permisos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles_permisos
    ADD CONSTRAINT roles_permisos_pkey PRIMARY KEY (idrolpermiso);


--
-- TOC entry 4693 (class 2606 OID 24587)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (idrol);


--
-- TOC entry 4691 (class 2606 OID 16401)
-- Name: usuarios usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (idusuario);


--
-- TOC entry 4728 (class 2620 OID 24785)
-- Name: asientos_contables audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.asientos_contables FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4729 (class 2620 OID 24800)
-- Name: bancos audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.bancos FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4726 (class 2620 OID 24789)
-- Name: clasificaciones audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.clasificaciones FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4727 (class 2620 OID 24786)
-- Name: cuentas audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.cuentas FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4725 (class 2620 OID 24788)
-- Name: permisos audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.permisos FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4724 (class 2620 OID 24790)
-- Name: roles audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.roles FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4723 (class 2620 OID 24787)
-- Name: usuarios audit_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();


--
-- TOC entry 4721 (class 2606 OID 24738)
-- Name: asiento_cuenta asiento_cuenta_asiento_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asiento_cuenta
    ADD CONSTRAINT asiento_cuenta_asiento_id_fkey FOREIGN KEY (asiento_id) REFERENCES public.asientos_contables(idasiento);


--
-- TOC entry 4722 (class 2606 OID 24743)
-- Name: asiento_cuenta asiento_cuenta_cuenta_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asiento_cuenta
    ADD CONSTRAINT asiento_cuenta_cuenta_id_fkey FOREIGN KEY (cuenta_id) REFERENCES public.cuentas(idcuenta);


--
-- TOC entry 4717 (class 2606 OID 24674)
-- Name: cuentas cuentas_id_padre_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT cuentas_id_padre_fkey FOREIGN KEY (id_padre) REFERENCES public.cuentas(idcuenta) ON DELETE SET NULL;


--
-- TOC entry 4718 (class 2606 OID 24719)
-- Name: cuentas fk_cuentas_clasificaciones; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT fk_cuentas_clasificaciones FOREIGN KEY (clasificacion_id) REFERENCES public.clasificaciones(idclasificacion);


--
-- TOC entry 4715 (class 2606 OID 24607)
-- Name: roles_permisos fk_permisos_rolespermisos; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles_permisos
    ADD CONSTRAINT fk_permisos_rolespermisos FOREIGN KEY (permiso_id) REFERENCES public.permisos(idpermiso);


--
-- TOC entry 4716 (class 2606 OID 24602)
-- Name: roles_permisos fk_roles_rolespermisos; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles_permisos
    ADD CONSTRAINT fk_roles_rolespermisos FOREIGN KEY (rol_id) REFERENCES public.roles(idrol);


--
-- TOC entry 4719 (class 2606 OID 24700)
-- Name: cuentas fk_usuario_modificacion; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT fk_usuario_modificacion FOREIGN KEY (usuario_id) REFERENCES public.usuarios(idusuario);


--
-- TOC entry 4720 (class 2606 OID 24726)
-- Name: asientos_contables fk_usuario_modificacion; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.asientos_contables
    ADD CONSTRAINT fk_usuario_modificacion FOREIGN KEY (usuario_id) REFERENCES public.usuarios(idusuario);


--
-- TOC entry 4714 (class 2606 OID 24612)
-- Name: usuarios fk_usuarios_roles; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT fk_usuarios_roles FOREIGN KEY (idrol) REFERENCES public.roles(idrol);


-- Completed on 2024-09-22 23:37:10

--
-- PostgreSQL database dump complete
--

