import React, { useState } from "react";
import "../styles/ViewAllModal.css";

function ViewAllModal({ open, onClose, title, data, columns, onDelete, onSelectForUpdate }) {
  const [expandedIdx, setExpandedIdx] = useState(null);

  console.log("ViewAllModal data", data);

  if (!open) return null;
  return (
    <div className="vamodal-overlay" onClick={onClose}>
      <div className="vamodal" onClick={e => e.stopPropagation()}>
        <button className="vamodal-close" onClick={onClose}>×</button>
        <h2 className="vamodal-title">{title}</h2>
        <div className="vamodal-table-wrapper">
          <table className="vamodal-table">
            <thead>
              <tr>
                {columns.map(col => (
                  <th key={col} className={col === "image_url" ? "image_url-col" : undefined}>{col}</th>
                ))}
                <th className="vamodal-action-col">Actions</th>
              </tr>
            </thead>
            <tbody>
              {data && data.length > 0 ? data.map((row, idx) => (
                <tr key={row.id || idx}>
                  {columns.map(col => {
                    if (col === "image_url") {
                      return (
                        <td
                          key={col}
                          className={"image_url-col" + (expandedIdx === idx ? " expanded" : "")}
                          title={expandedIdx === idx ? row[col] : undefined}
                          onClick={e => { e.stopPropagation(); setExpandedIdx(expandedIdx === idx ? null : idx); }}
                          style={{cursor: 'pointer'}}
                        >
                          {expandedIdx === idx
                            ? row[col]
                            : row[col]?.length > 30 ? row[col].slice(0, 30) + '…' : row[col]}
                        </td>
                      );
                    }                    if (col === "edition_id" && row.edition) {
                      return <td key={col}>{row.edition.id}</td>;
                    }
                    if (col === "engine_id" && row.engine) {
                      return <td key={col}>{row.engine.id}</td>;
                    }
                    return <td key={col}>{row[col]}</td>;
                  })}                  <td className="vamodal-action-col">
                    <div style={{ display: 'flex', justifyContent: 'flex-end' }}>
                      {onSelectForUpdate && (
                        <button 
                          className="vamodal-update-btn" 
                          onClick={() => {
                            onSelectForUpdate(row);
                            onClose();
                          }}
                          title="Modifier"
                        >
                          Modifier
                        </button>
                      )}
                      {onDelete && (
                        <button 
                          className="vamodal-delete-btn" 
                          onClick={() => onDelete(row)} 
                          title="Supprimer"
                        >
                          Supprimer
                        </button>
                      )}
                    </div>
                  </td>
                </tr>
              )) : (
                <tr><td colSpan={columns.length + 1} style={{textAlign:'center'}}>Aucune donnée</td></tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

export default ViewAllModal;