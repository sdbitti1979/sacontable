PGDMP                      |            SA_DESARROLLO    17rc1    17rc1 _    !           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            "           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            #           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            $           1262    16388    SA_DESARROLLO    DATABASE     �   CREATE DATABASE "SA_DESARROLLO" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Argentina.1252';
    DROP DATABASE "SA_DESARROLLO";
                     postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
                     pg_database_owner    false            %           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                        pg_database_owner    false    5                       1255    24784    audit_trigger_function()    FUNCTION     ]  CREATE FUNCTION public.audit_trigger_function() RETURNS trigger
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
 /   DROP FUNCTION public.audit_trigger_function();
       public               postgres    false    5            �            1259    24732    asiento_cuenta    TABLE     �   CREATE TABLE public.asiento_cuenta (
    idasientocuenta integer NOT NULL,
    asiento_id integer,
    cuenta_id integer,
    debe double precision,
    haber double precision
);
 "   DROP TABLE public.asiento_cuenta;
       public         heap r       postgres    false    5            �            1259    24731 "   asiento_cuenta_idasientocuenta_seq    SEQUENCE     �   CREATE SEQUENCE public.asiento_cuenta_idasientocuenta_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE public.asiento_cuenta_idasientocuenta_seq;
       public               postgres    false    5    233            &           0    0 "   asiento_cuenta_idasientocuenta_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE public.asiento_cuenta_idasientocuenta_seq OWNED BY public.asiento_cuenta.idasientocuenta;
          public               postgres    false    232            �            1259    24680    asientos_contables    TABLE     �   CREATE TABLE public.asientos_contables (
    idasiento integer NOT NULL,
    cuenta_id integer,
    fecha date NOT NULL,
    descripcion text,
    usuario_id integer NOT NULL
);
 &   DROP TABLE public.asientos_contables;
       public         heap r       postgres    false    5            �            1259    24679    asientos_contables_id_seq    SEQUENCE     �   CREATE SEQUENCE public.asientos_contables_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.asientos_contables_id_seq;
       public               postgres    false    231    5            '           0    0    asientos_contables_id_seq    SEQUENCE OWNED BY     ^   ALTER SEQUENCE public.asientos_contables_id_seq OWNED BY public.asientos_contables.idasiento;
          public               postgres    false    230            �            1259    24775 	   audit_log    TABLE     �  CREATE TABLE public.audit_log (
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
    DROP TABLE public.audit_log;
       public         heap r       postgres    false    5            �            1259    24774    audit_log_id_auditoria_seq    SEQUENCE     �   CREATE SEQUENCE public.audit_log_id_auditoria_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.audit_log_id_auditoria_seq;
       public               postgres    false    5    235            (           0    0    audit_log_id_auditoria_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public.audit_log_id_auditoria_seq OWNED BY public.audit_log.id_auditoria;
          public               postgres    false    234            �            1259    24792    bancos    TABLE     �   CREATE TABLE public.bancos (
    idbanco integer NOT NULL,
    descripcion character varying(200) NOT NULL,
    activo character(1) DEFAULT 'T'::bpchar NOT NULL
);
    DROP TABLE public.bancos;
       public         heap r       postgres    false    5            �            1259    24791    bancos_idbanco_seq    SEQUENCE     �   CREATE SEQUENCE public.bancos_idbanco_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.bancos_idbanco_seq;
       public               postgres    false    5    237            )           0    0    bancos_idbanco_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.bancos_idbanco_seq OWNED BY public.bancos.idbanco;
          public               postgres    false    236            �            1259    24652    clasificaciones    TABLE     y   CREATE TABLE public.clasificaciones (
    idclasificacion integer NOT NULL,
    nombre character varying(50) NOT NULL
);
 #   DROP TABLE public.clasificaciones;
       public         heap r       postgres    false    5            �            1259    24651    clasificaciones_id_seq    SEQUENCE     �   CREATE SEQUENCE public.clasificaciones_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.clasificaciones_id_seq;
       public               postgres    false    227    5            *           0    0    clasificaciones_id_seq    SEQUENCE OWNED BY     ^   ALTER SEQUENCE public.clasificaciones_id_seq OWNED BY public.clasificaciones.idclasificacion;
          public               postgres    false    226            �            1259    24661    cuentas    TABLE     �  CREATE TABLE public.cuentas (
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
    DROP TABLE public.cuentas;
       public         heap r       postgres    false    5            �            1259    24660    cuentas_id_seq    SEQUENCE     �   CREATE SEQUENCE public.cuentas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.cuentas_id_seq;
       public               postgres    false    229    5            +           0    0    cuentas_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.cuentas_id_seq OWNED BY public.cuentas.idcuenta;
          public               postgres    false    228            �            1259    24589    permisos    TABLE     r   CREATE TABLE public.permisos (
    idpermiso integer NOT NULL,
    descripcion character varying(100) NOT NULL
);
    DROP TABLE public.permisos;
       public         heap r       postgres    false    5            �            1259    24588    permisos_idpermiso_seq    SEQUENCE     �   CREATE SEQUENCE public.permisos_idpermiso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.permisos_idpermiso_seq;
       public               postgres    false    5    223            ,           0    0    permisos_idpermiso_seq    SEQUENCE OWNED BY     Q   ALTER SEQUENCE public.permisos_idpermiso_seq OWNED BY public.permisos.idpermiso;
          public               postgres    false    222            �            1259    24582    roles    TABLE     k   CREATE TABLE public.roles (
    idrol integer NOT NULL,
    descripcion character varying(100) NOT NULL
);
    DROP TABLE public.roles;
       public         heap r       postgres    false    5            �            1259    24581    roles_idrol_seq    SEQUENCE     �   CREATE SEQUENCE public.roles_idrol_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.roles_idrol_seq;
       public               postgres    false    5    221            -           0    0    roles_idrol_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.roles_idrol_seq OWNED BY public.roles.idrol;
          public               postgres    false    220            �            1259    24596    roles_permisos    TABLE     �   CREATE TABLE public.roles_permisos (
    idrolpermiso integer NOT NULL,
    rol_id integer NOT NULL,
    permiso_id integer NOT NULL
);
 "   DROP TABLE public.roles_permisos;
       public         heap r       postgres    false    5            �            1259    24595    roles_permisos_idrolpermiso_seq    SEQUENCE     �   CREATE SEQUENCE public.roles_permisos_idrolpermiso_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.roles_permisos_idrolpermiso_seq;
       public               postgres    false    225    5            .           0    0    roles_permisos_idrolpermiso_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE public.roles_permisos_idrolpermiso_seq OWNED BY public.roles_permisos.idrolpermiso;
          public               postgres    false    224            �            1259    16396    usuarios    TABLE     �  CREATE TABLE public.usuarios (
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
    DROP TABLE public.usuarios;
       public         heap r       postgres    false    5            �            1259    16395    usuarios_idusuario_seq    SEQUENCE     �   CREATE SEQUENCE public.usuarios_idusuario_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.usuarios_idusuario_seq;
       public               postgres    false    219    5            /           0    0    usuarios_idusuario_seq    SEQUENCE OWNED BY     Q   ALTER SEQUENCE public.usuarios_idusuario_seq OWNED BY public.usuarios.idusuario;
          public               postgres    false    218            M           2604    24735    asiento_cuenta idasientocuenta    DEFAULT     �   ALTER TABLE ONLY public.asiento_cuenta ALTER COLUMN idasientocuenta SET DEFAULT nextval('public.asiento_cuenta_idasientocuenta_seq'::regclass);
 M   ALTER TABLE public.asiento_cuenta ALTER COLUMN idasientocuenta DROP DEFAULT;
       public               postgres    false    232    233    233            L           2604    24683    asientos_contables idasiento    DEFAULT     �   ALTER TABLE ONLY public.asientos_contables ALTER COLUMN idasiento SET DEFAULT nextval('public.asientos_contables_id_seq'::regclass);
 K   ALTER TABLE public.asientos_contables ALTER COLUMN idasiento DROP DEFAULT;
       public               postgres    false    231    230    231            N           2604    24778    audit_log id_auditoria    DEFAULT     �   ALTER TABLE ONLY public.audit_log ALTER COLUMN id_auditoria SET DEFAULT nextval('public.audit_log_id_auditoria_seq'::regclass);
 E   ALTER TABLE public.audit_log ALTER COLUMN id_auditoria DROP DEFAULT;
       public               postgres    false    234    235    235            P           2604    24795    bancos idbanco    DEFAULT     p   ALTER TABLE ONLY public.bancos ALTER COLUMN idbanco SET DEFAULT nextval('public.bancos_idbanco_seq'::regclass);
 =   ALTER TABLE public.bancos ALTER COLUMN idbanco DROP DEFAULT;
       public               postgres    false    236    237    237            E           2604    24655    clasificaciones idclasificacion    DEFAULT     �   ALTER TABLE ONLY public.clasificaciones ALTER COLUMN idclasificacion SET DEFAULT nextval('public.clasificaciones_id_seq'::regclass);
 N   ALTER TABLE public.clasificaciones ALTER COLUMN idclasificacion DROP DEFAULT;
       public               postgres    false    227    226    227            F           2604    24664    cuentas idcuenta    DEFAULT     n   ALTER TABLE ONLY public.cuentas ALTER COLUMN idcuenta SET DEFAULT nextval('public.cuentas_id_seq'::regclass);
 ?   ALTER TABLE public.cuentas ALTER COLUMN idcuenta DROP DEFAULT;
       public               postgres    false    229    228    229            C           2604    24592    permisos idpermiso    DEFAULT     x   ALTER TABLE ONLY public.permisos ALTER COLUMN idpermiso SET DEFAULT nextval('public.permisos_idpermiso_seq'::regclass);
 A   ALTER TABLE public.permisos ALTER COLUMN idpermiso DROP DEFAULT;
       public               postgres    false    223    222    223            B           2604    24585    roles idrol    DEFAULT     j   ALTER TABLE ONLY public.roles ALTER COLUMN idrol SET DEFAULT nextval('public.roles_idrol_seq'::regclass);
 :   ALTER TABLE public.roles ALTER COLUMN idrol DROP DEFAULT;
       public               postgres    false    220    221    221            D           2604    24599    roles_permisos idrolpermiso    DEFAULT     �   ALTER TABLE ONLY public.roles_permisos ALTER COLUMN idrolpermiso SET DEFAULT nextval('public.roles_permisos_idrolpermiso_seq'::regclass);
 J   ALTER TABLE public.roles_permisos ALTER COLUMN idrolpermiso DROP DEFAULT;
       public               postgres    false    225    224    225            A           2604    16399    usuarios idusuario    DEFAULT     x   ALTER TABLE ONLY public.usuarios ALTER COLUMN idusuario SET DEFAULT nextval('public.usuarios_idusuario_seq'::regclass);
 A   ALTER TABLE public.usuarios ALTER COLUMN idusuario DROP DEFAULT;
       public               postgres    false    219    218    219                      0    24732    asiento_cuenta 
   TABLE DATA           ]   COPY public.asiento_cuenta (idasientocuenta, asiento_id, cuenta_id, debe, haber) FROM stdin;
    public               postgres    false    233   P|                 0    24680    asientos_contables 
   TABLE DATA           b   COPY public.asientos_contables (idasiento, cuenta_id, fecha, descripcion, usuario_id) FROM stdin;
    public               postgres    false    231   m|                 0    24775 	   audit_log 
   TABLE DATA           w   COPY public.audit_log (id_auditoria, esquema, fecha, jsons, operacion, pk_campo, pk_valor, tabla, usuario) FROM stdin;
    public               postgres    false    235   �|                 0    24792    bancos 
   TABLE DATA           >   COPY public.bancos (idbanco, descripcion, activo) FROM stdin;
    public               postgres    false    237   ��                 0    24652    clasificaciones 
   TABLE DATA           B   COPY public.clasificaciones (idclasificacion, nombre) FROM stdin;
    public               postgres    false    227   ��                 0    24661    cuentas 
   TABLE DATA           �   COPY public.cuentas (idcuenta, nombre, codigo, clasificacion_id, saldo, id_padre, utilizada, eliminada, modificado, solo_admin, usuario_id) FROM stdin;
    public               postgres    false    229   �                 0    24589    permisos 
   TABLE DATA           :   COPY public.permisos (idpermiso, descripcion) FROM stdin;
    public               postgres    false    223   %�                 0    24582    roles 
   TABLE DATA           3   COPY public.roles (idrol, descripcion) FROM stdin;
    public               postgres    false    221   ~�                 0    24596    roles_permisos 
   TABLE DATA           J   COPY public.roles_permisos (idrolpermiso, rol_id, permiso_id) FROM stdin;
    public               postgres    false    225   ��                 0    16396    usuarios 
   TABLE DATA           �   COPY public.usuarios (idusuario, usuario, email, clave, apellido, nombre, ultimo_inicio_sesion, cuil, idrol, activo) FROM stdin;
    public               postgres    false    219   �       0           0    0 "   asiento_cuenta_idasientocuenta_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('public.asiento_cuenta_idasientocuenta_seq', 1, false);
          public               postgres    false    232            1           0    0    asientos_contables_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.asientos_contables_id_seq', 1, false);
          public               postgres    false    230            2           0    0    audit_log_id_auditoria_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public.audit_log_id_auditoria_seq', 12, true);
          public               postgres    false    234            3           0    0    bancos_idbanco_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.bancos_idbanco_seq', 1, false);
          public               postgres    false    236            4           0    0    clasificaciones_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.clasificaciones_id_seq', 6, true);
          public               postgres    false    226            5           0    0    cuentas_id_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.cuentas_id_seq', 15, true);
          public               postgres    false    228            6           0    0    permisos_idpermiso_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.permisos_idpermiso_seq', 6, true);
          public               postgres    false    222            7           0    0    roles_idrol_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.roles_idrol_seq', 2, true);
          public               postgres    false    220            8           0    0    roles_permisos_idrolpermiso_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('public.roles_permisos_idrolpermiso_seq', 7, true);
          public               postgres    false    224            9           0    0    usuarios_idusuario_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public.usuarios_idusuario_seq', 14, true);
          public               postgres    false    218            e           2606    24737 "   asiento_cuenta asiento_cuenta_pkey 
   CONSTRAINT     m   ALTER TABLE ONLY public.asiento_cuenta
    ADD CONSTRAINT asiento_cuenta_pkey PRIMARY KEY (idasientocuenta);
 L   ALTER TABLE ONLY public.asiento_cuenta DROP CONSTRAINT asiento_cuenta_pkey;
       public                 postgres    false    233            c           2606    24687 *   asientos_contables asientos_contables_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY public.asientos_contables
    ADD CONSTRAINT asientos_contables_pkey PRIMARY KEY (idasiento);
 T   ALTER TABLE ONLY public.asientos_contables DROP CONSTRAINT asientos_contables_pkey;
       public                 postgres    false    231            g           2606    24783    audit_log audit_log_pkey 
   CONSTRAINT     `   ALTER TABLE ONLY public.audit_log
    ADD CONSTRAINT audit_log_pkey PRIMARY KEY (id_auditoria);
 B   ALTER TABLE ONLY public.audit_log DROP CONSTRAINT audit_log_pkey;
       public                 postgres    false    235            i           2606    24798    bancos bancos_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY public.bancos
    ADD CONSTRAINT bancos_pkey PRIMARY KEY (idbanco);
 <   ALTER TABLE ONLY public.bancos DROP CONSTRAINT bancos_pkey;
       public                 postgres    false    237            [           2606    24659 *   clasificaciones clasificaciones_nombre_key 
   CONSTRAINT     g   ALTER TABLE ONLY public.clasificaciones
    ADD CONSTRAINT clasificaciones_nombre_key UNIQUE (nombre);
 T   ALTER TABLE ONLY public.clasificaciones DROP CONSTRAINT clasificaciones_nombre_key;
       public                 postgres    false    227            ]           2606    24657 $   clasificaciones clasificaciones_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY public.clasificaciones
    ADD CONSTRAINT clasificaciones_pkey PRIMARY KEY (idclasificacion);
 N   ALTER TABLE ONLY public.clasificaciones DROP CONSTRAINT clasificaciones_pkey;
       public                 postgres    false    227            _           2606    24673    cuentas cuentas_codigo_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT cuentas_codigo_key UNIQUE (codigo);
 D   ALTER TABLE ONLY public.cuentas DROP CONSTRAINT cuentas_codigo_key;
       public                 postgres    false    229            a           2606    24671    cuentas cuentas_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT cuentas_pkey PRIMARY KEY (idcuenta);
 >   ALTER TABLE ONLY public.cuentas DROP CONSTRAINT cuentas_pkey;
       public                 postgres    false    229            W           2606    24594    permisos permisos_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.permisos
    ADD CONSTRAINT permisos_pkey PRIMARY KEY (idpermiso);
 @   ALTER TABLE ONLY public.permisos DROP CONSTRAINT permisos_pkey;
       public                 postgres    false    223            Y           2606    24601 "   roles_permisos roles_permisos_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY public.roles_permisos
    ADD CONSTRAINT roles_permisos_pkey PRIMARY KEY (idrolpermiso);
 L   ALTER TABLE ONLY public.roles_permisos DROP CONSTRAINT roles_permisos_pkey;
       public                 postgres    false    225            U           2606    24587    roles roles_pkey 
   CONSTRAINT     Q   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (idrol);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public                 postgres    false    221            S           2606    16401    usuarios usuarios_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (idusuario);
 @   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_pkey;
       public                 postgres    false    219            x           2620    24785     asientos_contables audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.asientos_contables FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 9   DROP TRIGGER audit_trigger ON public.asientos_contables;
       public               postgres    false    285    231            y           2620    24800    bancos audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.bancos FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 -   DROP TRIGGER audit_trigger ON public.bancos;
       public               postgres    false    237    285            v           2620    24789    clasificaciones audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.clasificaciones FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 6   DROP TRIGGER audit_trigger ON public.clasificaciones;
       public               postgres    false    227    285            w           2620    24786    cuentas audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.cuentas FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 .   DROP TRIGGER audit_trigger ON public.cuentas;
       public               postgres    false    229    285            u           2620    24788    permisos audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.permisos FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 /   DROP TRIGGER audit_trigger ON public.permisos;
       public               postgres    false    285    223            t           2620    24790    roles audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.roles FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 ,   DROP TRIGGER audit_trigger ON public.roles;
       public               postgres    false    285    221            s           2620    24787    usuarios audit_trigger    TRIGGER     �   CREATE TRIGGER audit_trigger AFTER INSERT OR DELETE OR UPDATE ON public.usuarios FOR EACH ROW EXECUTE FUNCTION public.audit_trigger_function();
 /   DROP TRIGGER audit_trigger ON public.usuarios;
       public               postgres    false    285    219            q           2606    24738 -   asiento_cuenta asiento_cuenta_asiento_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.asiento_cuenta
    ADD CONSTRAINT asiento_cuenta_asiento_id_fkey FOREIGN KEY (asiento_id) REFERENCES public.asientos_contables(idasiento);
 W   ALTER TABLE ONLY public.asiento_cuenta DROP CONSTRAINT asiento_cuenta_asiento_id_fkey;
       public               postgres    false    233    4707    231            r           2606    24743 ,   asiento_cuenta asiento_cuenta_cuenta_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.asiento_cuenta
    ADD CONSTRAINT asiento_cuenta_cuenta_id_fkey FOREIGN KEY (cuenta_id) REFERENCES public.cuentas(idcuenta);
 V   ALTER TABLE ONLY public.asiento_cuenta DROP CONSTRAINT asiento_cuenta_cuenta_id_fkey;
       public               postgres    false    233    229    4705            m           2606    24674    cuentas cuentas_id_padre_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT cuentas_id_padre_fkey FOREIGN KEY (id_padre) REFERENCES public.cuentas(idcuenta) ON DELETE SET NULL;
 G   ALTER TABLE ONLY public.cuentas DROP CONSTRAINT cuentas_id_padre_fkey;
       public               postgres    false    229    229    4705            n           2606    24719 "   cuentas fk_cuentas_clasificaciones    FK CONSTRAINT     �   ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT fk_cuentas_clasificaciones FOREIGN KEY (clasificacion_id) REFERENCES public.clasificaciones(idclasificacion);
 L   ALTER TABLE ONLY public.cuentas DROP CONSTRAINT fk_cuentas_clasificaciones;
       public               postgres    false    4701    229    227            k           2606    24607 (   roles_permisos fk_permisos_rolespermisos    FK CONSTRAINT     �   ALTER TABLE ONLY public.roles_permisos
    ADD CONSTRAINT fk_permisos_rolespermisos FOREIGN KEY (permiso_id) REFERENCES public.permisos(idpermiso);
 R   ALTER TABLE ONLY public.roles_permisos DROP CONSTRAINT fk_permisos_rolespermisos;
       public               postgres    false    223    4695    225            l           2606    24602 %   roles_permisos fk_roles_rolespermisos    FK CONSTRAINT     �   ALTER TABLE ONLY public.roles_permisos
    ADD CONSTRAINT fk_roles_rolespermisos FOREIGN KEY (rol_id) REFERENCES public.roles(idrol);
 O   ALTER TABLE ONLY public.roles_permisos DROP CONSTRAINT fk_roles_rolespermisos;
       public               postgres    false    221    225    4693            o           2606    24700    cuentas fk_usuario_modificacion    FK CONSTRAINT     �   ALTER TABLE ONLY public.cuentas
    ADD CONSTRAINT fk_usuario_modificacion FOREIGN KEY (usuario_id) REFERENCES public.usuarios(idusuario);
 I   ALTER TABLE ONLY public.cuentas DROP CONSTRAINT fk_usuario_modificacion;
       public               postgres    false    4691    219    229            p           2606    24726 *   asientos_contables fk_usuario_modificacion    FK CONSTRAINT     �   ALTER TABLE ONLY public.asientos_contables
    ADD CONSTRAINT fk_usuario_modificacion FOREIGN KEY (usuario_id) REFERENCES public.usuarios(idusuario);
 T   ALTER TABLE ONLY public.asientos_contables DROP CONSTRAINT fk_usuario_modificacion;
       public               postgres    false    4691    231    219            j           2606    24612    usuarios fk_usuarios_roles    FK CONSTRAINT     z   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT fk_usuarios_roles FOREIGN KEY (idrol) REFERENCES public.roles(idrol);
 D   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT fk_usuarios_roles;
       public               postgres    false    4693    219    221                  x������ � �            x������ � �         �  x���[o�H �g�)�����`��Ŋ�V�E����[�� Xm��w_��x�m�&��O"��ǟ�� i��1$1����e�C��I�ԓhU�k�o:��QqXw����M�ַ�k����:�������IQ�>��8��>���$�M�5������G�4K���<ʎp�dT�a�1���6�'#�'ӳkF�c�U�*�"WυT�E�)j��J���$Y���{cP��@Ec :D:d�0)x�3�y\��(�(E�I9��������1�����tPpy��.���*r���Uׇwcg���3�5���.�R��Z��*/��޺�,��b���i���A�$��(́7_�Ӳ,*��`��iY�-/��mˮ5�z��y�{s�ntQ��fQ���t�r]k��{᲏<^�i��'�0��E�Z�����7��۬�i.�:Ru"�7��V$$�mD�,�XZ�#`�P� ��F�!&B���t�FΜ��{'$Pc`���f27)������G�,����<X:�m�/�^r[����I�[�#ӱ{/��k��]�׶z��U��o$�7u�R��t� #D�u�*e��U�;k�m`ǆ׸��
�Q��a�� f|�W�����~�ǃ�io��t���$�}�S��쪿5��n;F�<������K2�;�3�;B�3(�1��}������E�p�%hE9Z�je��`F R�Q{��z7h�*Y���@�f�E�h��*�V�)��(o�q#MUT�i"���_�7h��з��
t���ɨ��nb�r�����C_��n���^���-������ܺ��w9���''&��nD ����x+�*�T����@�w��WȋRārD����0TQE��B@p��� ���3!���S0
��r��ݫQ�:�'؞5���Ժwp���B�R=23�C0�>>����c�g�|��v�v�B��%m~ݰ��3�c�f�&*Rn�wN)7I�,+X� �?���/p��0{����������_#yc�            x������ � �         O   x�3�tt���2�p1���� O_?O.� ��P�G� �`O�RS$A?WwG������c����#W� ��           x�u�=N�0���)��f�g� Q,B�r��3ZY�ږKp$
.@����5���y�W�#95���JEDY
��ܕ%\��y�OE��y��J�؀Z8��4��) �/&6�7�9�:�G��&Q�u�.�
��ErZI��6��~���V䕅}n�	�ɱ^��7��Jr�(������K*��N�Ⰴ\~7E8e-+�#��$t�<�����C�������D�+N4�{�#;T��f�H�/�"�l;�]Q���J         I   x�3�u���s���	q�2Br
#\]<AJL�D|<}=��b�Hbή>\f� 7�=... �c�         #   x�3�tt����	rt��2��p�0c���� ��_         -   x�ɹ 0��7L ��2���aC�B�M�m����冟w��M�         v  x���Ms�0�s�=�Z$!�(HQ�g/���"T^T��K���v��?�~�'D :�K�]��~E���θPgY2!��
���g����jKGP\�Y$;6�Ӹ����\��un��u،+��W���ǣ��y`�Ӊ������ �	M$�D "+��*R ~Bp:�l�d�=<[ދ&�^��֕"y��py2����q��]z_�K<w��D�^ݵl��z�=��5�|� �=!��$�	Y&��#چ�U��;��y��˳(�Q����f\6)�׸ۺô����듾�&uu��~���a�����
V���%�{]���W�7Jy��,��,�"���-�/��V�J&��k.�k3��nϦs:��џ:$iXl�$D(I��@�ä�����q+��h9����X�c�r��g'�+�#��5qtk�u0ͽ�<�OA����`fR�������ڃ�5L>��P�E�N��-�9h����`e��1tz	Ε7`�ߌ�����������8�����`����dyk��sb�meӱ��������r[`���"��f���������O��3�s��������ؖH@����qO�T����B���j�3P     