const allDevices = [
  {Sku: "mdt10", description: 'RMI Patriot 10" MDC', serial: '', asset: ''},
  {Sku: "mdt12", description: 'RMI Patriot 12" MDC', serial: '', asset: ''},
  {Sku: "PLA2", description: 'RMI Installation Plate A', serial: ''},
  {Sku: "PLB2", description: 'RMI Installation Plate B', serial: ''},
  {Sku: "RF930", description: 'RMI IQ Modem/Tait Radio', serial : '', asset: ''},
  {Sku: "R931", description: 'Cradlepoint IMEI No', serial: '', asset: ''},
  {Sku: "PSEC5", description: 'Parsec Antenna (5 in 1)', serial: '', asset: ''},
  {Sku: "PSEC7", description: 'Parsec Antenna (7 in 1)', serial: '', asset: ''},
  {Sku: "ORB61", description: 'Orbcomm ST6100 Serial No', serial: '', asset: ''},
  {Sku: "VFA1", description: 'VHF Radio Antenna', serial: '', asset: ''},
  {Sku: "PSC", description: 'PSC S Code', serial: '', asset: ''},
  {Sku: "HB12", description: 'RMI RMHB 12"', serial: '', asset: ''},
  {Sku: "HB15", description: 'RMI RMHB 15"', serial: '', asset: ''},
  {Sku: "EMRSW1", description: 'RMI EMER Button Kit', serial: '', asset: ''},
];

const installationTypeMapping = {
  type1: ["mdt12", "PLA2", "RF930", "R931", "PSEC5", "PSEC7", "ORB61", "VFA1", "PSC"],
  type2: ["mdt12", "HB12", "PLA2", "RF930", "R931", "PSEC5", "PSEC7", "ORB61", "VFA1", "PSC"],
  type3: ["mdt12", "R931", "PSEC5", "PSEC7", "ORB61"],
  type4: ["PLA2", "R931", "PSEC5", "PSEC7", "ORB61"],
  type5: ["EMRSW1", "ORB61"]
};

const disabledAssetTags = [
  'RMI Installation Plate A',
  'RMI Installation Plate B',
  'PSC S Code',
  'VHF Radio Antenna',
  'Parsec Antenna (5 in 1)',
  'Parsec Antenna (7 in 1)',
  'RMI EMER Button Kit'
];
