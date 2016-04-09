
ppi_1 = [('SLT2', 'SDP1'), ('GCD7', 'ATG34'), ('RAD26', 'END3'), 
('TES1', 'PRP40'), ('YNL095C', 'SGE1'), ('YKR043C', 'YKR043C'), 
('ADE8', 'MED4'), ('RSP5', 'SIP2'), ('YPT52', 'YMR258C'), ('EPT1', 'YET1'),
 ('FET3', 'PNS1'), ('MYO5', 'DYN3'), ('SMT3', 'NIS1'), ('ASK10', 'ASK10'), 
 ('TSC13', 'STE24'), ('COS8', 'GWT1'), ('MMM1', 'SUR4'), ('SMT3', 'NSE5'), 
 ('URE2', 'URE2'), ('TIF35', 'TIF35'), ('WTM2', 'WTM1'), ('ADY3', 'ADY3'), 
 ('YPC1', 'EMP47'), ('FPS1', 'FPS1'), ('RLI1', 'NVJ1'), ('SSM4', 'MVP1'), 
 ('HMG1', 'HMG1'), ('DAD3', 'OPI1'), ('RVS167', 'BBP1'), ('BUD14', 'SUR7'),
 ('HEM12', 'HEM12'), ('PBY1', 'RPS28A'), ('PRP40', 'AMD1'), ('RVS167', 'EAF6'), 
 ('GIC1', 'CDC12'), ('RMR1', 'SRL2'), ('RCE1', 'GAS4'), ('YPL014W', 'HSP82'), 
 ('DPS1', 'HSP82'), ('LAG1', 'YOR223W'), ('CAN1', 'ELO1'), ('NVJ1', 'NVJ1'), 
 ('LSM8', 'RPS28A'), ('YGR130C', 'YGR130C'), ('BZZ1', 'BZZ1'), ('RGA1', 'RHO1'),
 ('YET1', 'GPI2'), ('MTH1', 'MTH1'), ('ADY2', 'ADY2'), ('ERD1', 'LPP1')]

ppi_2 = [('TIF35', 'TIF35'), ('PTP1', 'SSM4'), ('AMD1', 'PRP40'), 
('ATG15', 'COS4'), ('CDC43', 'CDC43'), ('SMD2', 'SMD2'), ('SUR4', 'MMM1'),
 ('GZF3', 'GZF3'), ('IRC24', 'IRC24'), ('PRP11', 'SPC24'), 
 ('PAU12', 'PAU12'), ('BOI2', 'NAM7'), ('CDC28', 'BUD4'), 
 ('FEN2', 'YNR065C'), ('PHO88', 'PHO87'), ('ATG17', 'CAF40'), 
 ('LSM2', 'KEM1'), ('DBP7', 'DBP7'), ('TAE1', 'SNZ3'), ('GIC1', 'GIC1'), 
 ('IFA38', 'TLG1'),('GNA1', 'GPD2'), ('YHR113W', 'MAD2'), ('YIR024C', 'DCP2'),
  ('YPT1', 'PEP12'), ('UIP3', 'YKT6'), ('YSC84', 'SLA2'), ('PSF1', 'PSF1'), 
  ('BAS1', 'BAS1'), ('UIP3', 'MSB2'), ('YDR374C', 'ATG17'), ('HSH155', 'CUS2'),
   ('CDC28', 'LRS4'), ('YNR029C', 'YNR029C'), ('RSP5', 'SIP2'), 
   ('SLY1', 'SLY1'), ('MBP1', 'VTI1'), ('YSC84', 'ACF2'), ('SWD1', 'SWD1'),
    ('ICS2', 'MIF2'), ('HXT7', 'HXT7'), ('CTA1', 'MUK1'),('YKL069W', 'YKL069W'),
     ('GDH2', 'GDH2'), ('SAP1', 'SMC5'), ('TAE1', 'LSM3'), ('CDC28', 'ASH1'),
('CIK1', 'CIK1'), ('PEX29', 'ALG2'), ('GIC1', 'CLA4')]

match = [el for el in ppi_1 if any((el[0]==y[0] and el[1]==y[1]) or 
	(el[0]==y[1] and el[0]==y[1]) for y in ppi_2)]
sortMatch = []
for item in match:
	sortMatch.append(sorted(item))
sortMatch = sorted(sortMatch)
for item in sortMatch:
	print str(item[0]) + "\t" + str(item[1])