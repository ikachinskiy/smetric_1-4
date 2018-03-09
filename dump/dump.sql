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
-- Data for Name: users; Type: TABLE DATA; Schema: data; Owner: postgres
--

COPY users  FROM stdin;
\.


SET search_path = public, pg_catalog;

--
-- Data for Name: articles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY articles (id, author, numauthfrc, title, magazine, volume, number, startpage, endpage, catwoscore, catwosrsci, catscopus, catvak, catrinc, catothers, allauthors, year, fintype) FROM stdin;
134db278-b2fa-43cf-a04c-ed556c0205c9	6e18210d-474b-4885-9741-f337238e9d22	1	Методы и проблемы определения направлений перспективных научных исследований\r\n                \r\n                \r\n                \r\n                \r\n                \r\n                \r\n                \r\n                \r\n                \r\n                	Выявление приоритетных научных направлений: междисциплинарный подход	12	3	16	18	t	f	f	t	f	f	Сазонов Б.В.	2016	4
0949ceb8-0737-4042-9bb8-f6102fd26fa8	610c7fd7-b8b3-4fe2-b7ac-1c8630524922	1	Образ ребёнка в процессе обучения с зеркалом\n                	Лицо человека в пространстве общения			111	117	f	f	f	t	f	f	Лейбин В.М.	2017	4
93dbb11b-c98a-4198-955c-492cfff1fd10	7b1833af-160c-4f1f-b58d-0a82a6d27efa	3	Статистический анализ научных коммуникаций в академическом сообществе	Выявление приоритетных научных направлений: междисциплинарный подход	\N	\N	203	213	\N	\N	\N	\N	t	\N	Тищенко В.И., Жукова Т.И., Лисютин А.	2016	4
69a97444-06df-411a-a7f1-c572be6a6acc	d165acd2-af5b-479d-bf2b-e87c49f94156	3	Принципы сетевой методологии познания (статья первая)	Выявление приоритетных научных направлений: междисциплинарный подход	\N	\N	120	139	\N	\N	\N	\N	t	\N	\N	2017	4
80f4a121-d248-42a2-a4be-2500494a0008	75fc54c2-7bf6-40b1-9570-77ea64779faa	1	Управление качеством воздуха на гермозамкнутых объектах\n                \n                \n                \n                \n                \n                \n                \n                	Труды ИСА РАН		4	54	456	f	f	f	f	t	f	Осипов С.Н., Мещеряков А.Ю.	2016	4
c362acc9-a29d-4741-9dfc-8cc7ddb64bfe	610c7fd7-b8b3-4fe2-b7ac-1c8630524922	1	Мужество и стойкость Зигмунда Фрейда\n                	Известия Российской академии образования		2	145	151	f	f	f	t	f	f	Лейбин В.М.	2017	4
e0e89cd7-84be-4407-9796-da7869e9a049	4a98209f-e2ac-4a02-b636-c4d87e5286a7	1	Лейбниц об академии наук в России: Забыть? Игнорировать? Осознать?\n                \n                \n                	Общественные науки и современность		5	104	114	f	f	f	f	f	f	Чернозуб С.П.	2016	4
0da29119-053c-4fa1-b263-8502755d29d5	75fc54c2-7bf6-40b1-9570-77ea64779faa	2	Экономический рост Казахстана\n         \n                \n                \n                \n                \n                \n                \n                \n                \n                \n                \n                	Труды ИСА РАН		4	25	30	f	f	f	f	t	f	Осипов С.Н., Дубовский С.В.	2017	4
ee818d4a-c5e3-4b96-aac4-30b0d2f95f8c	d165acd2-af5b-479d-bf2b-e87c49f94156	4	Статистический анализ научных коммуникаций в академическом сообществе	Выявление приоритетных научных направлений: междисциплинарный подход	\N	\N	159	168	\N	\N	\N	\N	t	\N	\N	2016	4
4d6752f2-2b46-4aa0-9913-eed1e07029b1	d165acd2-af5b-479d-bf2b-e87c49f94156	4	Моделирование предметно-тематических областей в коммуникационных сетях	Выявление приоритетных научных направлений: междисциплинарный подход	\N	\N	179	190	\N	\N	\N	\N	t	\N	\N	2017	4
68137b2d-b0b7-4b3c-8f60-8bd9d0b76fb1	d165acd2-af5b-479d-bf2b-e87c49f94156	2	Основания современных методов прогнозирования и определения приоритетов развития науки	Выявление приоритетных научных направлений: междисциплинарный подход	\N	\N	6	15	\N	\N	\N	\N	t	\N	\N	2016	4
309a87cf-e310-45e7-a543-78c7626d0859	4a98209f-e2ac-4a02-b636-c4d87e5286a7	1	Оценка перспективности научного результата в свете особенностей различных международных систем цитирования и статуса российской науки\n                \n                	Выявление приоритетных научных направлений: междисциплинарный подход			29	43	f	f	f	f	f	f	Чернозуб С.П.	2017	4
\.


--
-- Data for Name: chapters; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY chapters (id, author, allauthors, titlechapter, titlemono, editor, publisher, pubplace, pubyear, pages) FROM stdin;
85eebe9b-f293-465c-adcb-59876590c8bf	71676346-e4ed-4afa-b2d3-e400c648efdd	Качинский И.Э.	Интродакшн	Ненаучная фантастика	Иванов И.И.	Мурзилка	Москва	2017	12
d305e508-cfe5-440c-be02-f696f0fcd6b4	6e18210d-474b-4885-9741-f337238e9d22	Сазонов Б.В.	Про разное	Научная фантастика	Сидоров С.С.	Самиздат	СПб	2016	666
14ea0718-305e-4aed-9014-19fcf3420635	6e18210d-474b-4885-9741-f337238e9d22	Сазонов и весёлая компания	Заключение (объективное)	Пособие по изготовлению ядрёной бомбы на уроках труда	Сам	Педгиз СССР	Гадюкино	1958	33
\.


--
-- Data for Name: discouncil; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY discouncil (id, number, chairman) FROM stdin;
1	Д 002.073.02	академик РАН И.А.Соколов
2	Д 002.073.05	академик РАН Ю.И.Журавлёв
3	Д 002.073.03	академик РАН Ю.Г.Евтушенко
4	Д 002.073.04	академик РАН Ю.С.Попков
5	Д 002.073.06	академик РАН С.В.Емельянов
\.


--
-- Data for Name: empl; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY empl (id, personalid, empfamily, empname, empsoname, empsex, empmainemail, empwtel, empmtel, empmessenger, empscistatus, empsciposition, empdivision, empjobposition, empjobstatus, emppassword, empbday, role_empl, role_manager, role_analit, role_admin, is_active) FROM stdin;
47a8dd08-544b-4085-9543-e4165685c0fb	\N	Тестовый	Тест	Тестович	\N	t@mail.ru				1	1	\N	\N	t	$2y$10$a.wGBrUd0dhwx9V7eTo5PeLuBHwYUPLM4wdrPzEnrwZlGZVxM/A9q	1959-03-12	t	f	f	f	t
d165acd2-af5b-479d-bf2b-e87c49f94156	4	Тищенко	Виктор	Иванович	t	3@mail.ru	+7(499)165234615	+7(916)2154872154	vit	7	5	\N	\N	t	$2y$10$675TYvMMQNGLtYJwExrAyuRx6r1d2.6VjyG0AuQ7CsNmqG5XVXx3q	2015-11-20	t	t	f	f	t
4a98209f-e2ac-4a02-b636-c4d87e5286a7	1	Чернозуб	Светлана	Петровна	f	4@mail.ru	+7(499)52182147	+7(916)5487215	spch	7	5	\N	\N	t	$2y$10$675TYvMMQNGLtYJwExrAyuRx6r1d2.6VjyG0AuQ7CsNmqG5XVXx3q	2017-11-01	t	f	t	f	t
75fc54c2-7bf6-40b1-9570-77ea64779faa	\N	Осипов	Сергей	Николаевич	\N	isa@isa.ru				7	5	\N	\N	t	$2y$10$lEGhqgQ9R1su1OmlAKljP.PhoAzuJmF0O75An0kvGncUXttmiD4wy	1961-05-17	t	t	t	f	t
71676346-e4ed-4afa-b2d3-e400c648efdd	\N	Качинский	Иван	Эдуардович	t	ika@smartnet.ru	+7(499)1353279	+7(916)6402584	qq	5	4	\N	\N	t	$2y$10$rKshd3zU50axvI4cAZrZi..hCWvUodWD888G5E01Bsw8fqiNm.c8G	1959-03-16	t	f	f	t	t
7b1833af-160c-4f1f-b58d-0a82a6d27efa	5	Жукова	Татьяна	Ивановна	f	2@mail.ru	+7(499)98748214	+7(916)26354872154	ICQ:123	7	5	\N	\N	t	$2y$10$675TYvMMQNGLtYJwExrAyuRx6r1d2.6VjyG0AuQ7CsNmqG5XVXx3q	1968-12-27	t	f	f	f	t
610c7fd7-b8b3-4fe2-b7ac-1c8630524922	2	Лейбин	Валерий	Моисеевич	t	5@mail.ru	+7(499)9871234856	+7(916)6215474	vml	7	5	\N	\N	t	$2y$10$675TYvMMQNGLtYJwExrAyuRx6r1d2.6VjyG0AuQ7CsNmqG5XVXx3q	1959-11-23	t	f	f	f	t
6e18210d-474b-4885-9741-f337238e9d22	3	Сазонов	Борис	Васильевич	t	1@mail.ru	+7(499)1234545	+7(916)842487241	bvs	5	3	\N	\N	t	$2y$10$675TYvMMQNGLtYJwExrAyuRx6r1d2.6VjyG0AuQ7CsNmqG5XVXx3q	1965-11-23	t	t	f	f	t
1bb9c265-dc8f-4def-8f50-1bdbfa2c5717	\N	Сидоров	Сидор	Сидорыч	\N	s@mail.ru				6	4	\N	\N	t	$2y$10$0PoRE4Ilnrp6i5ovHvSDf.tTFb8kIuQDlV8zR6twUXDcPCR/53Tbq	2017-12-13	t	f	f	f	t
\.


--
-- Data for Name: emplgroups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY emplgroups (empid, groupid) FROM stdin;
71676346-e4ed-4afa-b2d3-e400c648efdd	1
71676346-e4ed-4afa-b2d3-e400c648efdd	4
7b1833af-160c-4f1f-b58d-0a82a6d27efa	1
75fc54c2-7bf6-40b1-9570-77ea64779faa	1
d165acd2-af5b-479d-bf2b-e87c49f94156	1
4a98209f-e2ac-4a02-b636-c4d87e5286a7	1
610c7fd7-b8b3-4fe2-b7ac-1c8630524922	1
1bb9c265-dc8f-4def-8f50-1bdbfa2c5717	1
47a8dd08-544b-4085-9543-e4165685c0fb	1
\.


--
-- Data for Name: fintype; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY fintype (id, finsource) FROM stdin;
1	грант РФФИ
2	грант РНФ
3	Программа РАН
4	Гос.задание
5	Контракт
\.


--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY groups (id, "group") FROM stdin;
1	сотрудник
2	руководитель
3	аналитик
4	сисадмин
\.


--
-- Data for Name: monos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY monos (id, author, allauthors, numbauthfrc, title, publisher, pubplace, pubyear, pages, isbn, circulation) FROM stdin;
888f4d17-7be0-43b1-89e0-6d6dfe3ead86	71676346-e4ed-4afa-b2d3-e400c648efdd	Качинский И.Э.	1	Книжка про всё	Наука	Москва	2017	128	\N	5000
e86fc9b1-5d94-4eb6-af38-9c3e1d2857c7	6e18210d-474b-4885-9741-f337238e9d22	Сазонов Б.В. и сыновья	5	Учебник большой, толстый и умный\n                \n                \n                \n                \n                \n                \n                \n                \n                \n                \n                \n                \n                	Академия ремёсел и художеств	СПб	2017	333	1863862539826	300
\.


--
-- Data for Name: patents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY patents (id, author, allauthors, numbauthfrc, title, type, regnum, priordate, regdate, pubnum) FROM stdin;
a679d0cc-390b-4306-ad25-fe78c7c76c93	6e18210d-474b-4885-9741-f337238e9d22	Сазонов и товарищи	3	Программка мобильная\n                	4	34563456	2017-10-11	2017-12-01	123
36f0c8ef-6b7b-46e9-be78-9fc95242af08	6e18210d-474b-4885-9741-f337238e9d22	Сазонов и коллектив	1	Вечный двигатель думателя на лампочке-няонке	2	98765543	2013-06-13	2015-07-18	666
\.


--
-- Data for Name: patentstype; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY patentstype (description, id) FROM stdin;
Российский патент на полезную модель	3
Международный патент	1
Российский патент на изобретение	2
Свидетельство регистрации ПО или БД	4
\.


--
-- Data for Name: reports; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY reports (id, allauthors, numauthfrc, title, reptype, eventstartdate, eventenddate, magazine, publisher, eventname, eventplace, pubplace, startpage, endpage, catwos, catscopus, catissn, catisbn, catothers, pubyear, author) FROM stdin;
6efda5e0-a6c7-4f16-bf97-b3181c951fce	Чернозуб С.П.\n                \n                	1	Коллегия, а не "клуб учёных". Идея Лейбница о создании Академии       наук в России и перипетии её истолкования\n                \n                	t	2016-12-01	2016-12-03	ИФ РАН	ИФ РАН	Императорская академия наук и художеств, Академия наук СССР, Российская академия наук - триединая академия. К 290-летию основания РАН	Москва	Москва	23	34	f	f	f	t	f	2016	4a98209f-e2ac-4a02-b636-c4d87e5286a7
ea525f49-bf94-44f4-ba65-d7ac2bf24460	Лейбин В.М.	1	Образ ребёнка в процессе обучения с зеркалом                	t	2016-10-13	2016-10-14	Сборник трудов	ФИЦ ИУ РАН	Всесоюзная научная конференция "Лицо человека в пространстве общения"	Москва	Москва	0	0	f	f	t	f	f	2016	610c7fd7-b8b3-4fe2-b7ac-1c8630524922
bdcf204c-d9d1-4335-9845-dd8d3f60e9a6	Осипов С.Н., Дубовский С.В.	2	Расчёт экономического роста США, Китая, России                	t	2017-06-12	2017-05-16	Сборник трудов конференции	УРСС	САИТ 2017	Калиниград	Москва	25	29	f	f	t	t	f	2017	75fc54c2-7bf6-40b1-9570-77ea64779faa
23d5ebab-3386-4e64-a3cc-1fadcbe23e3d	Sazonov Boris, Korolev Anton, Kozhevnikov Dmitry\n                \n                	3	2747 Day II, Topic 3: Permanent Designing as a way to socio-technical systems sustainnability achieving         \n                \n                	f	2016-07-24	2016-07-30	Part 1	ISSS-2016	60th Annual Confenece and Meeting of the Internetional Society for the Systems Sciences "Realizing Sustainable Futures in Socio-Ecological Systems"	Boulder, Colorado	Boulder, Colorado	24	30	f	f	t	f	f	2016	6e18210d-474b-4885-9741-f337238e9d22
23389ec6-33ea-413c-a24d-5d08b3fbdb43	Сазонов Б.В., Королев А.С., Кожевников Д.Е.\n                \n                	3	Социотехнические системы как объект перманентного проектирования и управления\n                \n                	f	2016-10-03	2016-10-05	Материалы 9-й международной конференции MLSD'2016, том 2	ИПУ РАН	Конференция "Управление развитием крупномасштабных систем MLSD'2106	Москва, Россия	Москва	345	347	f	f	f	f	t	2016	6e18210d-474b-4885-9741-f337238e9d22
\.


--
-- Data for Name: sciment; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY sciment (id, personalid, scitype, description, regdate, disauthor, distitle, disstatus, disspeciality, disdefdate, disvakdate, discouncil) FROM stdin;
82ab75dc-9731-4ddd-a5d7-0db9520b0eae	6e18210d-474b-4885-9741-f337238e9d22	1	\N	2017-12-15	Сазонов Б.В.	Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги Про мозги 	2	10	2017-12-15	2017-12-15	2
53924024-cb84-4819-a9bd-20ab834e1213	7b1833af-160c-4f1f-b58d-0a82a6d27efa	4	\N	2017-12-15	Ванька Жуков	Наука имеет множество гитик	4	10	2017-12-01	2017-12-05	2
26be4b31-f69a-42cd-bfde-fa94bec4a382	6e18210d-474b-4885-9741-f337238e9d22	4	\N	2017-12-15	Жуков Ванька	Наука имеет множество гитик	4	5	2017-11-17	2017-12-14	3
2aa0304f-a53c-44c1-94fb-b453124bfc25	6e18210d-474b-4885-9741-f337238e9d22	3	\N	2017-12-15	Иванов И.И.	Про всё....	3	6	2017-12-01	2017-12-04	4
\.


--
-- Data for Name: sciposition; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY sciposition (id, sciposition) FROM stdin;
1	академик
2	член-корр.
3	профессор
4	доцент
5	нет
\.


--
-- Data for Name: scistatus; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY scistatus (id, scistatus) FROM stdin;
1	д.ф.-м.н.
2	д.т.н.
3	д.э.н.
4	к.ф.-м.н.
5	к.т.н.
6	к.э.н.
7	нет
\.


--
-- Data for Name: scitype; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY scitype (id, description) FROM stdin;
1	Защита докторской диссертации 
2	Защита кандидатской диссертации
3	Консультирование соискателя докторской диссертации
4	Руководство соискателем кандидатской диссертации
\.


--
-- Data for Name: speciality; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY speciality (id, number, descr, discouncil) FROM stdin;
1	05.13.11	Математическое и программное обеспечение вычислительных машин, комплексов и компьютерных сетей (физико-математические науки, технические науки)	1
2	05.13.15	Вычислительные машины, комплексы и компьютерные сети (технические науки)	1
3	05.13.19	Методы и системы защиты информации, информационная безопасность (технические науки)	1
4	01.01.09	Дискретная математика и математическая кибернетика (физико-математические науки)	2
5	05.13.17	Теоретические основы информатики (физико-математические науки, технические науки)	2
6	01.01.07	Вычислительная математика (физико-математические науки)	3
7	01.02.05	Механика жидкости, газа и плазмы (физико-математические науки)	3
8	01.01.03	Математическая физика (физико-математические науки)	3
9	05.13.01	Системный анализ, управление и обработка информации (информационно-вычислительное обеспечение) (физико-математические науки, технические науки)	4
10	05.13.10	Управление в социальных и экономических системах (технические науки)	4
11	05.13.18	Математическое моделирование, численные методы и комплексы программ (физико-математические науки, технические науки)	4
12	08.00.05	Экономика и управление народным хозяйством (региональная экономика) (экономические науки)	5
13	08.00.13	Математические и инструментальные методы экономики (экономические науки)	5
\.


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

