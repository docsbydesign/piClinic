---
title: "piClinic Console"
permalink: /cts/

---

# _piClinic Console_

[![piClinic Console System - Front]({{site.baseurl}}/images/TermFront-piclinic_sm.jpg)]({{site.baseurl}}/images/TermFront-piclinic_lg.jpg)

The _**piClinic** Console_ is a low-cost, computer solution that automates many of the most time-consuming administrative tasks in a small clinic to improve patient management and clinic reporting. Adopting the _**piClinic** Console_ helps the a limited-resource clinic move from a paper-based patient record system to automated systems while providing immediate benefits to the clinic staff and patients.

[![piClinic Console System - Rear]({{site.baseurl}}/images/TermRear_sm.jpg)]({{site.baseurl}}/images/TermRear_lg.jpg)

The _**piClinic** Console_ is built on open-source software, ubiquitous, low-cost hardware, and is designed for the clinic environment to offer an affordable and sustainable patient-information solution for clinics with limited resources around the world. The basic _**piClinic** Console_ configuration consists of a single monitor with the processor mounted on the back. The mouse and keyboard are connected to the processor.

The _**piClinic** Console_ can operate effectively in a clinic without requiring any additional resources such as printer supplies or other consumable products. At the same time, the _**piClinic** Console_ supports such devices as bar-code scanners, form and report printers, electronic data transfer using USB memories, and email of reports using cell phones. 

<a id="status" />
## Current Project Status

[Project milestones](#milestones)

| -------- | -------- |
| Prototype | In [Customer review and Beta-test site evaluations](#summer2018) |
| Demo | Online  |

Contact [Robert Watson](mailto://watson_rb@mercer.edu) for more info or to access the online demo.

## Technical details

The _**piClinic** Console_ consists of the hardware and software described in the following tables. The hardware to build a single _**piClinic** Console_  sells for about $240 through online stores such as Amazon. A battery-backed UPS to support power interuptions or poor ambient power quality, costs about $70 to run the system for 60 minutes.

### System hardware

| -------- | -------- |
| Processor | Raspberry Pi 3 model B|
| System storage | 32 GB micro-SDHC |
| Backup storage | 32 GB USB |
| Monitor | 19-22" LCD (HDMI)|
| Power | 110 VAC, 60 Hz, 20 watts (3 watts in power-save mode) |

[piClinic Console detailed parts list]({{site.baseurl}}/piclinic-console-parts/)

### System software

| -------- | -------- |
| Operating System | Linux Raspbian Stretch |
| Web Server | Apache |
| Application Software | PHP 7.0 |
| Database Software | MySQL 5.7 |

<a id="milestones" />
## Project Milestones

* [Current issues](https://github.com/MercerU-TCO/CTS/issues): Tasks that needs to be done.
* [Closed issues](https://github.com/MercerU-TCO/CTS/issues?q=is%3Aissue+is%3Aclosed): Tasks that have been completed.

### Phase 1a (Fall 2017 semester)

Goal: test software configuration and confirm data requirements<br>
[Milestone issues](https://github.com/MercerU-TCO/CTS/issues?q=is%3Aopen+is%3Aissue+milestone%3A2017-fall-alpha)

1. Hardware selected and configured 
1. Patient Master Index functionally complete
1. Patient ID images functionally complete
1. Visit tracking functionally complete
1. Patient visit images functionally complete
1. Initial report set working
	* Clinic daily log
	* Clinic monthly statistics

* No JavaScript in pages, HTML-only UI
* No UI styling
* No system management functionality

<a id="phase1b"></a>
### Phase 1b (Spring 2018 semester)

Goal: prepare for field test<br>
[Milestone issues](https://github.com/MercerU-TCO/CTS/issues?q=is%3Aopen+is%3Aissue+milestone%3A2018-02-spring-beta)

1. (&#10003;) Hardware tested and verified ready for field test
1. (&#10003;) Visit tracking functionally complete
1. (_) Sytem management functions complete
	* (&#10003;) User account configuration
	* (_) Clinic configuration
	* (_) Backup/restore
	* (_) System upgrade
	* (_) Data-security features implemented
1. (_) Onboard training complete
1. (_) Onboard help complete
1. (&#10003;) Field-test UI complete

<a id="summer2018"></a>
### Customer review and Beta-test site evaluations (Summer 2018)

* Identify five clinics to Beta-test the system
* Review system features and requirements with Honduran stakeholders
* Identify logistical assets for summer 2019
* Conduct usability tests

### Field-test preparation (Fall 2018, Spring 2019)

* Refactor system to be customer-ready by Summer 2019
* Revise system, training, and documentation for production
* Investigate multi-user clinic system and multi-user portable system

### Field-test deployment (Summer 2019)

* Deploy systems into beta-test clinics