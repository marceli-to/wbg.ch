
let years = [], 
    min = 1990, 
    max = new Date().getFullYear();

for(let i = max; i >= min; i--)
{
    years.push(i);
}

export default years;