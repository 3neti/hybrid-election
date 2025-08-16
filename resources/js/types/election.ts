/* Core entity types */
export interface CandidateData {
    code: string
    name?: string
    alias?: string
}

export interface VoteData {
    position_code?: string
    position?: { code: string }
    candidate_codes?: CandidateData[]
    candidates?: CandidateData[]
}

export interface BallotData {
    id: string
    code: string
    votes: VoteData[]
}

export interface TallyData {
    position_code: string
    candidate_code: string
    candidate_name: string
    count: number
}

    export interface ElectionReturnData {
        id: string
        code: string
        precinct: {
            id: string
            code: string
            location_name?: string | null
            latitude?: number | null
            longitude?: number | null
            electoral_inspectors?: Array<{ id: string; name: string; role?: string | null }>
        }
        tallies: TallyData[]
        ballots?: BallotData[]
        last_ballot?: BallotData
        signatures?: Array<{ id?: string; name?: string; role?: string | null; signed_at?: string | null }>
    }

/* Optional: small runtime guards for safer manual testing */
export function isElectionReturnData(x: unknown): x is ElectionReturnData {
    const er = x as any
    return !!er
        && typeof er.id === 'string'
        && typeof er.code === 'string'
        && er.precinct && typeof er.precinct === 'object'
        && Array.isArray(er.tallies)
}
