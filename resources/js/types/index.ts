export interface EvolutionNote {
    subjetivo: string;
    objetivo: string;
    analisis: string;
    plan: string;
    tratamientos_completados: number[]; // IDs
    recetas: PrescriptionItem[];
}

export interface PrescriptionItem {
    id?: number;
    medicamento: string;
    dosis: string;
    frecuencia: string;
    duracion: string;
}
