export type collector = {
  type: string;
  lastCollected: string;
  level: number;
  id: number;
};

export type resources = {
  id: number;
  metal: number;
  gas: number;
  gems: number;
};

export type idleShips = {
  lightFighter: number;
  heavyFighter: number;
  cruiser: number;
  transporter: number;
  battleships: number;
};

export type fleet = {
  id: number;
  lightFighter: number;
  heavyFighter: number;
  cruiser: number;
  transporter: number;
  battleship: number;
  name: string;
};

export type harbour = {
  id: number;
  light_fighter: number;
  heavy_fighter: number;
  cruiser: number;
  transporter: number;
  battleships: number;
  fleets: fleet[];
};

export type user = {
  username: string;
  email: string;
};

export type planet = {
  id: number;
  occupied: boolean;
  occupiedBy: user;
  name: string;
};

export type galaxy = {
  id: number;
  name: string;
  planets: planet[];
};

export type base = {
  id: number;
  name: string;
  level: number;
  createdAt: string;
  lastUpgraded: string;
  user: user;
  planet: planet;
  harbour: harbour;
  resources: resources;
  collectors: collector[];
};

export type barracks = {};
