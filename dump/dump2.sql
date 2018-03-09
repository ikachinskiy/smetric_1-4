--
-- PostgreSQL database dump
--

-- Dumped from database version 9.6.6
-- Dumped by pg_dump version 10.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

DROP DATABASE IF EXISTS smetric;
--
-- Name: smetric; Type: DATABASE; Schema: -; Owner: postgres
--

CREATE DATABASE smetric WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'ru_RU.UTF-8' LC_CTYPE = 'ru_RU.UTF-8';


ALTER DATABASE smetric OWNER TO postgres;

\connect smetric

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: data; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA data;


ALTER SCHEMA data OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


SET search_path = data, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: users; Type: TABLE; Schema: data; Owner: postgres
--

CREATE TABLE users (
);


ALTER TABLE users OWNER TO postgres;

--
-- Name: TABLE users; Type: COMMENT; Schema: data; Owner: postgres
--

COMMENT ON TABLE users IS 'Пользователи';


SET search_path = public, pg_catalog;

--
-- Name: articles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE articles (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    author uuid,
    numauthfrc integer,
    title character varying(1024),
    magazine character varying(256),
    volume character varying(12),
    number character varying(12),
    startpage integer,
    endpage integer,
    catwoscore boolean,
    catwosrsci boolean,
    catscopus boolean,
    catvak boolean,
    catrinc boolean,
    catothers boolean,
    allauthors character varying(512),
    year integer,
    fintype integer
);


ALTER TABLE articles OWNER TO postgres;

--
-- Name: chapters; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE chapters (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    author uuid,
    allauthors character varying(512),
    titlechapter character varying(512),
    titlemono character varying(512),
    editor character varying(256),
    publisher character varying(256),
    pubplace character varying(256),
    pubyear integer,
    pages integer
);


ALTER TABLE chapters OWNER TO postgres;

--
-- Name: discouncil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE discouncil (
    id integer NOT NULL,
    number character varying(64),
    chairman character varying(1024)
);


ALTER TABLE discouncil OWNER TO postgres;

--
-- Name: TABLE discouncil; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE discouncil IS 'Диссертационные советы';


--
-- Name: disscouncil_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE disscouncil_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE disscouncil_id_seq OWNER TO postgres;

--
-- Name: disscouncil_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE disscouncil_id_seq OWNED BY discouncil.id;


--
-- Name: empl; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE empl (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    personalid character varying(48),
    empfamily character varying(64),
    empname character varying(64),
    empsoname character varying(64),
    empsex boolean,
    empmainemail character varying(64),
    empwtel character varying(32),
    empmtel character varying(32),
    empmessenger character varying(32),
    empscistatus integer,
    empsciposition integer,
    empdivision uuid,
    empjobposition uuid,
    empjobstatus boolean,
    emppassword character varying(256),
    empbday date,
    role_empl boolean,
    role_manager boolean,
    role_analit boolean,
    role_admin boolean,
    is_active boolean DEFAULT true
);


ALTER TABLE empl OWNER TO postgres;

--
-- Name: emplgroups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE emplgroups (
    empid uuid NOT NULL,
    groupid integer NOT NULL
);


ALTER TABLE emplgroups OWNER TO postgres;

--
-- Name: TABLE emplgroups; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE emplgroups IS 'Распределение сотрудников по группам';


--
-- Name: fintype; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE fintype (
    id integer NOT NULL,
    finsource character varying(32)
);


ALTER TABLE fintype OWNER TO postgres;

--
-- Name: TABLE fintype; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE fintype IS 'Тип финансирования';


--
-- Name: fintype_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fintype_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE fintype_id_seq OWNER TO postgres;

--
-- Name: fintype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fintype_id_seq OWNED BY fintype.id;


--
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE groups (
    id integer NOT NULL,
    "group" character varying(64)
);


ALTER TABLE groups OWNER TO postgres;

--
-- Name: TABLE groups; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE groups IS 'Группы пользователей';


--
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE groups_id_seq OWNER TO postgres;

--
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE groups_id_seq OWNED BY groups.id;


--
-- Name: monos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE monos (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    author uuid NOT NULL,
    allauthors character varying(512),
    numbauthfrc integer,
    title character varying(512),
    publisher character varying(512),
    pubplace character varying(256),
    pubyear integer,
    pages integer,
    isbn bigint,
    circulation integer
);


ALTER TABLE monos OWNER TO postgres;

--
-- Name: patents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE patents (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    author uuid,
    allauthors character varying(512),
    numbauthfrc integer,
    title character varying(512),
    type smallint,
    regnum character varying(64),
    priordate date,
    regdate date,
    pubnum integer
);


ALTER TABLE patents OWNER TO postgres;

--
-- Name: patentstype; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE patentstype (
    description character varying(64),
    id integer NOT NULL
);


ALTER TABLE patentstype OWNER TO postgres;

--
-- Name: TABLE patentstype; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE patentstype IS 'Виды объектов ИС';


--
-- Name: patetstype_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE patetstype_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE patetstype_id_seq OWNER TO postgres;

--
-- Name: patetstype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE patetstype_id_seq OWNED BY patentstype.id;


--
-- Name: reports; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE reports (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    allauthors character varying(512) DEFAULT 1,
    numauthfrc integer,
    title character varying(512),
    reptype boolean DEFAULT false,
    eventstartdate date,
    eventenddate date,
    magazine character varying(512),
    publisher character varying(512),
    eventname character varying(512),
    eventplace character varying(512),
    pubplace character varying(128),
    startpage integer,
    endpage integer,
    catwos boolean DEFAULT false,
    catscopus boolean DEFAULT false,
    catissn boolean DEFAULT false,
    catisbn boolean DEFAULT false,
    catothers boolean DEFAULT false,
    pubyear integer,
    author uuid
);


ALTER TABLE reports OWNER TO postgres;

--
-- Name: sciment; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE sciment (
    id uuid DEFAULT gen_random_uuid() NOT NULL,
    personalid uuid NOT NULL,
    scitype integer,
    description character varying(1024),
    regdate date DEFAULT now(),
    disauthor character varying(256),
    distitle character varying(1024),
    disstatus integer,
    disspeciality integer,
    disdefdate date,
    disvakdate date,
    discouncil integer
);


ALTER TABLE sciment OWNER TO postgres;

--
-- Name: TABLE sciment; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE sciment IS 'Активности по подготовке научных кадров';


--
-- Name: sciposition; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE sciposition (
    id integer NOT NULL,
    sciposition character varying(24)
);


ALTER TABLE sciposition OWNER TO postgres;

--
-- Name: sciposition_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE sciposition_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE sciposition_id_seq OWNER TO postgres;

--
-- Name: sciposition_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE sciposition_id_seq OWNED BY sciposition.id;


--
-- Name: scistatus; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE scistatus (
    id integer NOT NULL,
    scistatus character varying(24)
);


ALTER TABLE scistatus OWNER TO postgres;

--
-- Name: scistatus_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE scistatus_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE scistatus_id_seq OWNER TO postgres;

--
-- Name: scistatus_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE scistatus_id_seq OWNED BY scistatus.id;


--
-- Name: scitype; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE scitype (
    id integer NOT NULL,
    description character varying(512)
);


ALTER TABLE scitype OWNER TO postgres;

--
-- Name: TABLE scitype; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE scitype IS 'Виды активностей по подготовке научных кадров';


--
-- Name: scitype_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE scitype_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE scitype_id_seq OWNER TO postgres;

--
-- Name: scitype_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE scitype_id_seq OWNED BY scitype.id;


--
-- Name: speciality; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE speciality (
    id integer NOT NULL,
    number character varying(64),
    descr character varying(512),
    discouncil integer
);


ALTER TABLE speciality OWNER TO postgres;

--
-- Name: TABLE speciality; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE speciality IS 'Специальности для защиты дисс.';


--
-- Name: speciality_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE speciality_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE speciality_id_seq OWNER TO postgres;

--
-- Name: speciality_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE speciality_id_seq OWNED BY speciality.id;


--
-- Name: discouncil id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY discouncil ALTER COLUMN id SET DEFAULT nextval('disscouncil_id_seq'::regclass);


--
-- Name: fintype id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fintype ALTER COLUMN id SET DEFAULT nextval('fintype_id_seq'::regclass);


--
-- Name: groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups ALTER COLUMN id SET DEFAULT nextval('groups_id_seq'::regclass);


--
-- Name: patentstype id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patentstype ALTER COLUMN id SET DEFAULT nextval('patetstype_id_seq'::regclass);


--
-- Name: sciposition id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY sciposition ALTER COLUMN id SET DEFAULT nextval('sciposition_id_seq'::regclass);


--
-- Name: scistatus id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY scistatus ALTER COLUMN id SET DEFAULT nextval('scistatus_id_seq'::regclass);


--
-- Name: scitype id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY scitype ALTER COLUMN id SET DEFAULT nextval('scitype_id_seq'::regclass);


--
-- Name: speciality id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY speciality ALTER COLUMN id SET DEFAULT nextval('speciality_id_seq'::regclass);


SET search_path = data, pg_catalog;


--
-- Name: disscouncil_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('disscouncil_id_seq', 5, true);


--
-- Name: fintype_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('fintype_id_seq', 5, true);


--
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('groups_id_seq', 4, true);


--
-- Name: patetstype_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('patetstype_id_seq', 4, true);


--
-- Name: sciposition_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('sciposition_id_seq', 5, true);


--
-- Name: scistatus_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('scistatus_id_seq', 7, true);


--
-- Name: scitype_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('scitype_id_seq', 5, true);


--
-- Name: speciality_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('speciality_id_seq', 13, true);


--
-- Name: articles articles_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY articles
    ADD CONSTRAINT articles_id_pk PRIMARY KEY (id);


--
-- Name: discouncil disscouncil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY discouncil
    ADD CONSTRAINT disscouncil_pkey PRIMARY KEY (id);


--
-- Name: empl empl_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY empl
    ADD CONSTRAINT empl_id_pk PRIMARY KEY (id);


--
-- Name: fintype fintype_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fintype
    ADD CONSTRAINT fintype_pkey PRIMARY KEY (id);


--
-- Name: groups groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- Name: patents patents_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patents
    ADD CONSTRAINT patents_id_pk PRIMARY KEY (id);


--
-- Name: patentstype patetstype_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY patentstype
    ADD CONSTRAINT patetstype_id_pk PRIMARY KEY (id);


--
-- Name: reports reports_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY reports
    ADD CONSTRAINT reports_id_pk PRIMARY KEY (id);


--
-- Name: sciment sciment_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY sciment
    ADD CONSTRAINT sciment_id_pk PRIMARY KEY (id);


--
-- Name: sciposition sciposition_id_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY sciposition
    ADD CONSTRAINT sciposition_id_pk PRIMARY KEY (id);


--
-- Name: scistatus scistatus_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY scistatus
    ADD CONSTRAINT scistatus_pkey PRIMARY KEY (id);


--
-- Name: scitype scitype_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY scitype
    ADD CONSTRAINT scitype_pkey PRIMARY KEY (id);


--
-- Name: speciality speciality_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY speciality
    ADD CONSTRAINT speciality_pkey PRIMARY KEY (id);


--
-- Name: articles_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX articles_id_uindex ON articles USING btree (id);


--
-- Name: disscouncil_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX disscouncil_id_uindex ON discouncil USING btree (id);


--
-- Name: empl_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX empl_id_uindex ON empl USING btree (id);


--
-- Name: fintype_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX fintype_id_uindex ON fintype USING btree (id);


--
-- Name: groups_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX groups_id_uindex ON groups USING btree (id);


--
-- Name: patents_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX patents_id_uindex ON patents USING btree (id);


--
-- Name: patetstype_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX patetstype_id_uindex ON patentstype USING btree (id);


--
-- Name: reports_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX reports_id_uindex ON reports USING btree (id);


--
-- Name: sciment_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX sciment_id_uindex ON sciment USING btree (id);


--
-- Name: sciposition_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX sciposition_id_uindex ON sciposition USING btree (id);


--
-- Name: scistatus_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX scistatus_id_uindex ON scistatus USING btree (id);


--
-- Name: scitype_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX scitype_id_uindex ON scitype USING btree (id);


--
-- Name: speciality_id_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX speciality_id_uindex ON speciality USING btree (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

