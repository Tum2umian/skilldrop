const fs = require("fs");
const path = require("path");

// Define supported file types
const supportedExtensions = [".php", ".js", ".css", ".html"];

// Function to clean and extract code based on file type
function extractCode(content, ext) {
    if (ext === ".html") {
        // Remove HTML tags to analyze only scripts/styles inside
        return content.replace(/<[^>]+>/g, "");
    } else if (ext === ".css") {
        // Remove CSS comments
        return content.replace(/\/\*[\s\S]*?\*\//g, "");
    } else if (ext === ".php") {
        // Remove PHP comments & only extract PHP code
        return content.replace(/<\?php|\?>/g, "").replace(/\/\/.*|\/\*[\s\S]*?\*\//g, "");
    }
    return content; // Return as is for JS and others
}

// Function to analyze Halstead complexity
function analyzeHalstead(code) {
    const operatorPattern = /[+\-*/%=<>!&|^~?:]/g; // Common operators
    const operandPattern = /\b[A-Za-z_]\w*\b/g; // Variables, function names

    const operators = new Set();
    const operands = new Set();
    let N1 = 0, N2 = 0;

    let operatorMatches = code.match(operatorPattern) || [];
    let operandMatches = code.match(operandPattern) || [];

    operatorMatches.forEach(op => {
        operators.add(op);
        N1++;
    });

    operandMatches.forEach(op => {
        operands.add(op);
        N2++;
    });

    let n1 = operators.size;
    let n2 = operands.size;
    let N = N1 + N2;
    let n = n1 + n2;
    let V = N * (Math.log2(n) || 1); // Avoid log(0)
    let D = (n1 / 2) * (N2 / (n2 || 1)); // Avoid division by zero
    let E = D * V;

    return { n1, n2, N1, N2, n, N, V, D, E };
}

// Function to scan directories recursively and analyze files
function scanProject(directory) {
    let results = [];

    function readFiles(dir) {
        fs.readdirSync(dir).forEach(file => {
            let fullPath = path.join(dir, file);
            let ext = path.extname(fullPath);

            if (fs.statSync(fullPath).isDirectory()) {
                readFiles(fullPath); // Recursively scan subdirectories
            } else if (supportedExtensions.includes(ext)) {
                let code = fs.readFileSync(fullPath, "utf-8");
                let cleanCode = extractCode(code, ext); // Clean based on file type
                let analysis = analyzeHalstead(cleanCode);
                results.push({ file: fullPath, type: ext, ...analysis });
            }
        });
    }

    readFiles(directory);
    return results;
}

// Run the analysis on the project root
const projectRoot = __dirname; // Change if needed
const analysisResults = scanProject(projectRoot);

// Print results
console.log(JSON.stringify(analysisResults, null, 2));
