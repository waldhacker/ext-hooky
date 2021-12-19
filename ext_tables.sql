CREATE TABLE tx_hooky_hook (
  url    VARCHAR(2048),
  secret VARCHAR(255),
  events JSON,
);


CREATE TABLE enqueue (
  id              char(36) COLLATE utf8_unicode_ci     NOT NULL COMMENT '(DC2Type:guid)',
  published_at    bigint                               NOT NULL,
  body            longtext COLLATE utf8_unicode_ci,
  headers         longtext COLLATE utf8_unicode_ci,
  properties      longtext COLLATE utf8_unicode_ci,
  redelivered     tinyint(1) DEFAULT NULL,
  queue           varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  priority        smallint                         DEFAULT NULL,
  delayed_until   bigint                           DEFAULT NULL,
  time_to_live    bigint                           DEFAULT NULL,
  delivery_id     char(36) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '(DC2Type:guid)',
  redeliver_after bigint                           DEFAULT NULL,
);
