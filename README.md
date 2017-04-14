# Graph

## Data Filestructure

- `verteces/`
	- `id-n/`
		- `p.json`
			- {[key:string]:string|number}
			- Properties of the vertex
		- `outs/`
			- `some-label.json`
				- array of edge ids
		- `ins/`
			- `some-label.json`
				- array of edge ids
- `edges/`
	- `id-n`
		- `e.json`
			- {[key:string]:string|number}
			- _required_ properties of the edge
				- label
				- inV
				- outV
		- `p.json`
			- {[key:string]:string|number}
