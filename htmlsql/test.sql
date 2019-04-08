
-- ----------------------------
-- Table structure for xy_classes
-- ----------------------------
DROP TABLE IF EXISTS "xy_classes";
CREATE TABLE "xy_classes" (
  "id" integer(255),
  "uid" integer(255),
  "uname" TEXT(255),
  "add_time" TEXT(255),
  "update_time" TEXT(255),
  "status" TEXT(255),
  "title" TEXT(255),
  "point" TEXT(255),
  "discount" TEXT(255),
  "point_rate" TEXT(255),
  "money" TEXT(255),
  "is_auto_up" TEXT(255),
  "offset_point" TEXT(255),
  "up_classes_id" TEXT(255),
  "expire_month" TEXT(255),
  "keep_money" TEXT(255),
  "keep_rate" TEXT(255)
);

-- ----------------------------
-- Records of xy_classes
-- ----------------------------
INSERT INTO "xy_classes" VALUES (1, 1, '张连超', '6/6/2015 22:00:57', '5/12/2018 09:49:57', 1, '普通会员', 0, 100, 100, 0, 1, 0, 7, 0, 0, 100);
INSERT INTO "xy_classes" VALUES (3, 1, '张连超', '4/12/2018 10:19:20', '14/12/2018 16:01:07', 1, '钻石会员 ', 16000, 85, 100, 8000, 1, 16000, 0, 12, 6800, 85);
INSERT INTO "xy_classes" VALUES (4, 1, '张连超', '4/12/2018 10:20:02', '14/12/2018 16:00:59', 1, '金牌会员', 8000, 88, 100, 3000, 1, 8000, 3, 12, 2550, 85);
INSERT INTO "xy_classes" VALUES (5, 1, '张连超', '4/12/2018 10:20:44', '14/12/2018 16:00:49', 1, '银牌会员', 5000, 90, 100, 2000, 1, 5000, 4, 12, 1800, 90);
INSERT INTO "xy_classes" VALUES (6, 1, '张连超', '4/12/2018 10:21:19', '14/12/2018 16:00:36', 1, '铜牌会员', 3000, 95, 100, 1000, 1, 3000, 5, 12, 800, 80);
INSERT INTO "xy_classes" VALUES (7, 1, '张连超', '4/12/2018 10:22:59', '14/12/2018 16:00:28', 1, 'VIP会员', 2000, 98, 100, 500, 1, 2000, 6, 13, 350, 70);


